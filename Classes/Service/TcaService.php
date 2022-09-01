<?php

namespace Ps\Xo\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
	 * Liefert eine Kategorie-Liste (Unterkategorien) anhand eines Keys aus der Extension Konfiguration, in dem die UID
	 * der Eltern-Kategorie hinterlegt ist
	 *
	 * @param array $configuration Current field configuration
	 * @internal
	 */
	public function getCategoriesByExtensionConfigurationIdentifier(array &$configuration) {
		$extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get($configuration['config']['itemsProcConfig']['extension']);
		$parentCategoryUid = $extensionConfiguration[$configuration['config']['itemsProcConfig']['identifier']];

		/** @var \TYPO3\CMS\Core\Database\Query\QueryBuilder  $queryBuilder */
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_category')->createQueryBuilder();
		$query = $queryBuilder->select('*')
			->from('sys_category')
			->where(
				$queryBuilder->expr()->in('sys_language_uid', [0, -1]),
				$queryBuilder->expr()->eq('parent', $queryBuilder->createNamedParameter($parentCategoryUid, \PDO::PARAM_INT))
			)
			->orderBy('sorting', 'asc');

		$statement = $query->execute();

		while($row = $statement->fetch()) {
			$configuration['items'][] = [$row['title'], $row['uid']];
		}
	}
}