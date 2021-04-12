<?php

namespace Ps\Xo\Service;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper;

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
}