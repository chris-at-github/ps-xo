<?php

namespace Ps\Xo\Hooks;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * 1. TYPO3 verarbeitet SysFileReference Felder die per columnsOverride auf l10n_mode=exclude gesetzt sind nur beim
 * 	ersten Uebersetzen korrekt. Loescht man diese Datensaetze in der Hauptsprache werden diese im Anschluss nicht
 * 	wieder angelegt
 * 2. Einfache Felder wie z.B. die per columnsOverride auf l10n_mode=exclude gesetzt werden ebenfalls nicht dauerhaft
 * 	angepasst
 */
class LocalizeL10nExcludeHook {

	/**
	 * @param DataHandler $dataHandler
	 */
	public function processDatamap_afterAllOperations(DataHandler &$dataHandler) {
		if(isset($dataHandler->datamap['tt_content']) === true) {
			$this->localizeRecords($dataHandler->datamap['tt_content'], 'tt_content', $dataHandler);
		}
	}

	/**
	 * @param array $records
	 * @param string $table
	 * @param DataHandler $dataHandler
	 * @return boolean|void
	 */
	protected function localizeRecords($records, $table, DataHandler &$dataHandler) {

		if(isset($GLOBALS['TCA'][$table]['ctrl']['type']) === false) {
			return false;
		}

		if(isset($GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField']) === false) {
			return false;
		}

		foreach($records as $uid => $record) {
			$this->localizeRecord($record, $table, $uid, $dataHandler);
		}
	}

	/**
	 * @param array $record
	 * @param string $table
	 * @param int $uid
	 * @param DataHandler $dataHandler
	 * @return boolean|void
	 */
	protected function localizeRecord($record, $table, $uid, DataHandler &$dataHandler) {
		$typeField = $GLOBALS['TCA'][$table]['ctrl']['type'];
		$transOrigPointerField = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'];

		// 1. gibt es ueberhaupt l10n_mode Felder, die ueber columnsOverride definiert sind
		//
		// gibt es fuer diese Tabelle ueberhaupt eine Typ-Definition?
		if(isset($record[$typeField]) === false) {
			return false;
		}

		// gibt es zu dem aktuellen Typ ein Eintrag in der Types-Konfiguration?
		// gibt es Columns-Override Eintraege fuer diesen Typ
		if(
			isset($GLOBALS['TCA'][$table]['types'][$record[$typeField]]) === false ||
			isset($GLOBALS['TCA'][$table]['types'][$record[$typeField]]['columnsOverrides']) === false
		) {
			return false;
		}

		// 2. alle Columns-Override Felder durchlaufen und pruefen ob es l10n_mode => exclude Feld gibt
		$l10nExludeFields = [];

		foreach($GLOBALS['TCA'][$table]['types'][$record[$typeField]]['columnsOverrides'] as $fieldName => $fieldConfiguration) {

			// 2.1: nur weiter machen wenn es hier einen Eintrag mit l10n_mode => exclude gibt -> sonst wird es bereits ueber
			// 	TYPO3 richtig verarbeitet
			// 2.2: es werden nun Inline-Felder mit aufgenommen
			// 2.3: es werden nur Felder aufgenommen, die Unterdatensaetze enthalten
			if(
				isset($fieldConfiguration['l10n_mode']) === true &&
				$fieldConfiguration['l10n_mode'] === 'exclude' &&
				isset($GLOBALS['TCA'][$table]['columns'][$fieldName]) === true &&
				empty($record[$fieldName]) === false
			) {
				$defaultFieldConfiguration = $GLOBALS['TCA'][$table]['columns'][$fieldName]['config'];

				if(isset($fieldConfiguration['config']) === true) {
					ArrayUtility::mergeRecursiveWithOverrule($defaultFieldConfiguration, $fieldConfiguration['config']);
				}

				$l10nExludeFields[$fieldName] = $defaultFieldConfiguration;
			}
		}

		if(empty($l10nExludeFields) === true) {
			return false;
		}

		// 3. Gibt es Uebersetzungen zu diesem Datensatz?
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table)->createQueryBuilder();
		$statement  = $queryBuilder
			->select('uid', 'sys_language_uid')
			->from($table)
			->where(
				$queryBuilder->expr()->eq($transOrigPointerField, $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
			)
			->execute();
		$translations = $statement->fetchAll();

		if(empty($translations) === true) {
			return false;
		}

		// 4. alle l10n Exclude Fields durchlaufen und uebersetzen falls notwendig
		foreach($l10nExludeFields as $fieldName => $fieldConfiguration) {

			if($fieldConfiguration['type'] === 'inline') {
				$this->localizeInlineField($fieldName, $fieldConfiguration, $table, $uid, $translations, $dataHandler);

			} elseif($fieldConfiguration['type'] === 'flex') {
				$this->localizeSimpleField($fieldName, $fieldConfiguration, $table, $uid, $translations, $dataHandler);
			}
		}
	}

	/**
	 * @param string $fieldName
	 * @param array $fieldConfiguration
	 * @param string $table
	 * @param array $translations
	 * @param int $uid
	 * @param DataHandler $dataHandler
	 * @return boolean|void
	 */
	protected function localizeInlineField($fieldName, $fieldConfiguration, $table, $uid, $translations, DataHandler &$dataHandler) {

		// 1. laesst sich diese Tabelle ueberhaupt uebersetzen
		if(isset($GLOBALS['TCA'][$fieldConfiguration['foreign_table']]['ctrl']['transOrigPointerField']) === false) {
			return false;
		}

		// 2. gibt es ueberhaupt Kinddatensaetze
		$inlineUids = GeneralUtility::trimExplode(',', $dataHandler->datamap[$table][$uid][$fieldName], true);

		if(empty($inlineUids) === true) {
			return false;
		}

		foreach($inlineUids as &$inlineUid) {
			if(strpos($inlineUid, 'NEW') !== false) {
				$inlineUid = $dataHandler->substNEWwithIDs[$inlineUid];
			}

			$inlineUid = (int) $inlineUid;
		}

		// 3. Kinddatensaetze der Hauptsprache laden
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($fieldConfiguration['foreign_table'])->createQueryBuilder();
		$statement  = $queryBuilder
			->select('*')
			->from($fieldConfiguration['foreign_table'])
			->where(
				$queryBuilder->expr()->in('uid', $inlineUids),
				$queryBuilder->expr()->eq('sys_language_uid', 0)
			)
			->execute();
		$data = $statement->fetchAll();

		if(empty($data) === true) {
			return false;
		}

		// 4. Uebersetzungen von Kinddatensaetzen anlegen (falls noch nicht vorhanden)
		foreach($data as $row) {
			foreach($translations as $translation) {

				// Ueberpruefung ob es bereits eine Uebersetzung zu diesem Datensatz gibt
				$connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($fieldConfiguration['foreign_table']);
				$isTranslated = $connection->count(
					'uid',
					$fieldConfiguration['foreign_table'],
					[
						$GLOBALS['TCA'][$fieldConfiguration['foreign_table']]['ctrl']['transOrigPointerField'] => $row['uid'],
						'sys_language_uid' => $translation['sys_language_uid']
					]
				);

				if($isTranslated === 0) {
					$insert = $row;

					// Daten fuer die Uebersetzung aufbereiten
					unset($insert['uid']);
					$insert['sys_language_uid'] = $translation['sys_language_uid'];
					$insert[$GLOBALS['TCA'][$fieldConfiguration['foreign_table']]['ctrl']['transOrigPointerField']] = $row['uid'];
					$insert[$fieldConfiguration['foreign_field']] = $translation['uid'];

					GeneralUtility::makeInstance(ConnectionPool::class)
						->getConnectionForTable($fieldConfiguration['foreign_table'])
						->insert($fieldConfiguration['foreign_table'], $insert);
				}
			}
		}
	}

	/**
	 * @param string $fieldName
	 * @param array $fieldConfiguration
	 * @param string $table
	 * @param array $translations
	 * @param int $uid
	 * @param DataHandler $dataHandler
	 * @return boolean|void
	 */
	protected function localizeSimpleField($fieldName, $fieldConfiguration, $table, $uid, $translations, DataHandler &$dataHandler) {

		// aktuellen Wert aus der Hauptsprache laden
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table)->createQueryBuilder();
		$statement  = $queryBuilder
			->select($fieldName)
			->from($table)
			->where(
				$queryBuilder->expr()->in('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)),
				$queryBuilder->expr()->eq('sys_language_uid', 0)
			)
			->execute();
		$data = $statement->fetch();

		foreach($translations as $translation) {
			$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
			$queryBuilder
				->update($table)
				->where(
					$queryBuilder->expr()->eq($GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'], $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)),
					$queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($translation['sys_language_uid'], \PDO::PARAM_INT))
				)
				->set($fieldName, $data[$fieldName])
				->execute();
		}
	}
}