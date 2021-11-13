<?php

namespace Ps\Xo\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper;
use TYPO3\CMS\Core\Database\ConnectionPool;

class TcaService {

	/**
	 * Object Manager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var array
	 */
	protected static $columnsOverrideTranslations = [];

	protected static $columnsOverrideTranslationsIdentifier = [];

	/**
	 * @return void
	 */
	public function __construct()	{
		$this->objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
	}

	/**
	 * liefert die TypoScript Plugin Einstellungen
	 *
	 * @return array
	 */
	public function getSettings($extension) {
		return $this->objectManager->get(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class)->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
			$extension
		);
	}

	/**
	 * Returns a list of category fields for a given table for populating selector "category_field"
	 * in tt_content table (called as itemsProcFunc).
	 *
	 * @param array $configuration Current field configuration
	 * @internal
	 */
	public function getItemsBySettingsIdentifier(array &$configuration) {
		$identifier = $configuration['config']['itemsProcConfig']['identifier'];
		$settings = $this->getSettings($configuration['config']['itemsProcConfig']['extension']);
		$items = ArrayUtility::getValueByPath($settings, $identifier, '.');

		foreach($items as $value => $label) {
			if(GeneralUtility::isFirstPartOfStr($label, 'LLL:') === true) {
				$label = LocalizationUtility::translate($label);
			}

			$configuration['items'][] = [$label, $value];
		}
	}

	/**
	 * @param string $status
	 * @param string $table
	 * @param int $id
	 * @param array $fields
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
	 */
	public function processDatamap_postProcessFieldArray($status, $table, $id, array $fields, \TYPO3\CMS\Core\DataHandling\DataHandler &$dataHandler) {
		if($table === 'tx_xo_domain_model_elements') {
			$this->synchronizeElementsTranslations($id);
		}
	}

//	/**
//	 * @param string $status
//	 * @param string $table
//	 * @param int $id
//	 * @param array $fields
//	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
//	 */
//	public function processDatamap_afterDatabaseOperations($status, $table, $id, array $fields, \TYPO3\CMS\Core\DataHandling\DataHandler &$dataHandler) {
//		if($table === 'tt_content') {
//			$this->prepareColumnsOverrideTranslations($id, $table, $dataHandler);
//		}
//	}
//
//	/**
//	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
//	 */
//	public function processDatamap_afterAllOperations(\TYPO3\CMS\Core\DataHandling\DataHandler &$dataHandler) {
//		if(empty(self::$columnsOverrideTranslations) === false) {
//			foreach(self::$columnsOverrideTranslations as $columnConfiguration) {
//				$this->synchronizeColumnsOverrideTranslations($columnConfiguration, $dataHandler);
//			}
//		}
//	}

	/**
	 * @param int $id
	 */
	protected function synchronizeElementsTranslations($id) {

		// 1. wenn ich in der Uebersetzung bin -> erstmal Elterndatensatz identifizieren
		/** @var QueryBuilder $queryBuilder */
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_xo_domain_model_elements')->createQueryBuilder();
		$statement  = $queryBuilder
			->select('l10n_parent')
			->from('tx_xo_domain_model_elements')
			->where(
				$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
			)
			->execute();
		$row = $statement->fetch();

		if((int) $row['l10n_parent'] !== 0) {
			$id = (int) $row['l10n_parent'];
		}


		// 2. Hauptdatensatz laden
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_xo_domain_model_elements')->createQueryBuilder();
		$statement  = $queryBuilder
			->select('sorting')
			->from('tx_xo_domain_model_elements')
			->where(
				$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
			)
			->execute();
		$row = $statement->fetch();


		// 3. Sortierung fuer Uebersetzungen uebernehmen
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
		$queryBuilder
			->update('tx_xo_domain_model_elements')
			->where(
				$queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
			)
			->set('sorting', (int) $row['sorting'])
			->execute();
	}

	/**
	 * @param int $id
	 * @param string $table
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
	 */
	protected function prepareColumnsOverrideTranslations($id, $table) {

		// Feld fuer Eltern-Id von uebersetzten Datensaetzen
		$transOrigPointerField = $GLOBALS['TCA'][$table]['ctrl']['transOrigPointerField'];

		if(empty($transOrigPointerField) === true) {
			return false;
		}

		// 1. Gibt es Uebersetzungen zu diesem Datensatz?
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table)->createQueryBuilder();
		$statement  = $queryBuilder
			->select('uid', 'sys_language_uid')
			->from('tt_content')
			->where(
				$queryBuilder->expr()->eq($transOrigPointerField, $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT))
			)
			->execute();
		$translations = $statement->fetchAll();

		if(empty($translations) === false) {

			// 2. aktuellen Datensatz auslesen
			$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table)->createQueryBuilder();
			$statement  = $queryBuilder
				->select('*')
				->from('tt_content')
				->where(
					$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($id, \PDO::PARAM_INT)),
					$queryBuilder->expr()->eq('sys_language_uid', 0)
				)
				->execute();
			$content = $statement->fetch();

			// 3. ColumnsOverride des Types auslesen und l10n_mode=exclude Felder identifzieren
			if(isset($GLOBALS['TCA'][$table]['types'][$content['CType']]) === true || isset($GLOBALS['TCA'][$table]['types'][$content['CType']]['columnsOverrides']) === true) {
				foreach($GLOBALS['TCA'][$table]['types'][$content['CType']]['columnsOverrides'] as $fieldName => $fieldConfiguration) {

					// nur weiter machen wenn es hier einen Eintrag mit l10n_mode => exclude gibt -> sonst wird es bereits ueber TYPO3 richtig
					// verarbeitet
					if(
						isset($fieldConfiguration['l10n_mode']) === true &&
						$fieldConfiguration['l10n_mode'] === 'exclude' &&
						isset($GLOBALS['TCA'][$table]['columns'][$fieldName]) === true
					) {

						// 4. gibt es Unterdatensaetze in der Hauptsprache -> auslesen und synchronisieren
						// es werden erstmal nur Inline-Datensaetze verarbeitet
						if(
							$GLOBALS['TCA'][$table]['columns'][$fieldName]['config']['type'] === 'inline' && (int) $content[$fieldName] !== 0) {
							self::$columnsOverrideTranslations[] = [
								'languages' => $translations,
								'uid' => $id,
								'fieldName' => $fieldName,
								'table' => $table,
							];
						}
					}
				}
			}
		}

		return false;
	}

	/**
	 * @param int $id
	 * @param string $table
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
	 */
	protected function synchronizeColumnsOverrideTranslations($columnConfiguration, \TYPO3\CMS\Core\DataHandling\DataHandler &$dataHandler) {

		$fieldInlineUids = GeneralUtility::trimExplode(',', $dataHandler->datamap[$columnConfiguration['table']][$columnConfiguration['uid']][$columnConfiguration['fieldName']], true);
		$fieldInlineConfiguration = $GLOBALS['TCA'][$columnConfiguration['table']]['columns'][$columnConfiguration['fieldName']]['config'];

//		DebuggerUtility::var_dump($dataHandler->datamap[$columnConfiguration['table']]);

		if(empty($fieldInlineUids) === true) {
			return false;
		}


		foreach($fieldInlineUids as &$fieldInlineUid) {
			if(strpos($fieldInlineUid, 'NEW') !== false) {
				$fieldInlineUid = $dataHandler->substNEWwithIDs[$fieldInlineUid];
			}

			$fieldInlineUid = (int) $fieldInlineUid;
		}

		// Ids der verknuepften Datensaetze auslesen
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($fieldInlineConfiguration['foreign_table'])->createQueryBuilder();
		$statement  = $queryBuilder
			->select('*')
			->from($fieldInlineConfiguration['foreign_table'])
			->where(
				$queryBuilder->expr()->in('uid', $fieldInlineUids),
				$queryBuilder->expr()->eq('sys_language_uid', 0)
			)
			->execute();

		$data = $statement->fetchAll();

		foreach($data as $row) {

			foreach($columnConfiguration['languages'] as $language) {

				// Ueberpruefung ob es bereits eine Uebersetzung zu diesem Datensatz gibt
				$connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($fieldInlineConfiguration['foreign_table']);
				$isTranslated = $connection->count(
					'uid',
					$fieldInlineConfiguration['foreign_table'],
					[
						'l10n_parent' => $row['uid'],
						'sys_language_uid' => $language['sys_language_uid']
					]
				);

				if($isTranslated === 0) {
					$insert = $row;

					// Daten fuer die Uebersetzung aufbereiten
					unset($insert['uid']);
					$insert['sys_language_uid'] = $language['sys_language_uid'];
					$insert['l10n_parent'] = $row['uid'];
					$insert['uid_foreign'] = $language['uid'];

					GeneralUtility::makeInstance(ConnectionPool::class)
						->getConnectionForTable($fieldInlineConfiguration['foreign_table'])
						->insert($fieldInlineConfiguration['foreign_table'], $insert);
				}
			}
		}

		//$queryBuilder->expr()->eq($fieldInlineConfiguration['foreign_field'], $queryBuilder->createNamedParameter($columnConfiguration['uid'], \PDO::PARAM_INT)),

//		$fieldInlineUids = array_map(function($row) {
//			return (int) $row['uid'];
//		}, $data);
/*
		if(empty($fieldInlineUids) === false) {
			foreach($fieldInlineUids as $inlineUid) {

				$identifier = md5($columnConfiguration['table'] . $columnConfiguration['fieldName'] . $inlineUid);

				if(in_array($identifier, self::$columnsOverrideTranslationsIdentifier) === false) {
					// Ids der verknuepften Datensaetze auslesen
					$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($fieldInlineConfiguration['foreign_table'])->createQueryBuilder();
					$statement  = $queryBuilder
						->select('uid')
						->from($fieldInlineConfiguration['foreign_table'])
						->where(
							$queryBuilder->expr()->eq('l10n_parent', $inlineUid, \PDO::PARAM_INT),
							$queryBuilder->expr()->eq('sys_language_uid', 1)
						)
						->execute();

					$data = $statement->fetch();

					if($data === false) {
						$cmd['sys_file_reference'][$inlineUid]['localize'] = 1;
						$dataHandler->start([], $cmd);
						$dataHandler->process_cmdmap();

						DebuggerUtility::var_dump($data);
					}
				}
//				$identifier = md5($columnConfiguration['table'] . $columnConfiguration['fieldName'] . $inlineUid);
//
//				if(in_array($identifier, self::$columnsOverrideTranslationsIdentifier) === false) {
//					self::$columnsOverrideTranslationsIdentifier[] = $identifier;
//
////					$cmd['sys_file_reference'][$inlineUid]['localize'] = 1;
////					$dataHandler->start([], $cmd);
////					$dataHandler->process_cmdmap();
////
////					DebuggerUtility::var_dump($cmd);
//				}
			}
		}
*/


		if(empty($fieldInlineUids) === false) {
			$identifier = md5($columnConfiguration['table'] . $columnConfiguration['fieldName'] . $columnConfiguration['uid']);

			if(in_array($identifier, self::$columnsOverrideTranslationsIdentifier) === false) {
				self::$columnsOverrideTranslationsIdentifier[] = $identifier;

				// ueber den DataHandler die Uebersetzungen anlegen
				// der DataHandler prueft selbststaendig ob und welche Unterdatensaetze angelegt werden muessen
				foreach($columnConfiguration['languages'] as $language) {
					$cmd[$columnConfiguration['table']][$columnConfiguration['uid']]['inlineLocalizeSynchronize'] = [ // 13 is a parent record uid
						'field' => $columnConfiguration['fieldName'], // field we want to synchronize
						'language' => 1, // uid of the target language
						// either the key 'action' or 'ids' must be set
						'action' => 'synchronize', // or 'synchronize'
						'ids' => $fieldInlineUids // array of child-ids to be localized
					];

					DebuggerUtility::var_dump($cmd);

//					$dataHandler->start([], $cmd);
//					$dataHandler->process_cmdmap();
				}
			}
		}
	}

}