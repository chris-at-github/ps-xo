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
//
//			/** @var Connection $connection */
//			$connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_registry');
//
//			$updateQuery = "UPDATE tx_xo_domain_model_elements
//      	SET $connection->quoteIdentifier('table1.field1') = $connection->quoteIdentifier('table2.field2')
//                WHERE $connection->quoteIdentifier('table1.uid') = $connection->quoteIdentifier('table2.uid')";
//			$connection->executeQuery($updateQuery);
		}
	}

	/**
	 *
	 */
	protected function synchronizeElementsTranslations($id) {

		// 1. wenn ich in der Uebersetzung bin -> erstmal Elterndatensatz identifizieren
		/** @var QueryBuilder $queryBuilder */
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_xo_domain_model_elements')->createQueryBuilder();
		$statement  = $queryBuilder
			->select('l10n_parent')
			->from('tx_xo_domain_model_elements')
			->orWhere(
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
			->orWhere(
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
}