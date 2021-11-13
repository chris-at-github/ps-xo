<?php

namespace Ps\Xo\Hooks;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class SynchronizeElementsTranslationsHook {

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
}