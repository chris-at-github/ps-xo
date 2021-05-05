<?php

/*
 * This file is part of the package bk2k/bootstrap-package.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Ps\Xo\DataProcessing;

use Ps\Xo\Service\InlineResourceService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class ModuleProcessor implements DataProcessorInterface {

	/**
	 * @var int
	 */
	static protected $moduleCounter = 0;

	/**
	 * @var string[]
	 */
	static protected $importedCssFiles = [];

	/**
	 * Object Manager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var string[]
	 */
	protected $importCssFiles = [];

	/**
	 * @return void
	 */
	public function __construct() {

		// Settings laden
		$this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);

		// Module Counter der Seite hochzaehlen
		self::$moduleCounter++;

		// CSS Dateien aus $importCssFiles verarbeiten
		$this->addImportCssFiles($this->importCssFiles);
	}

	/**
	 * liefert die TypoScript Plugin Einstellungen
	 *
	 * @return array
	 */
	protected function getSettings() {
		return $this->objectManager->get(ConfigurationManager::class)->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
			'xo'
		);
	}

	/**
	 * @return PageRenderer
	 */
	protected function getPageRenderer() {
		return $GLOBALS['TSFE']->pageRenderer;
	}

	/**
	 * Fuegt die Css Dateien im Head hinzu
	 *
	 * @return void
	 */
	protected function addImportCssFiles($importCssFiles) {

		/** @var InlineResourceService $inlineResourceService */
		$inlineResourceService = GeneralUtility::makeInstance(InlineResourceService::class);
		$settings = $this->getSettings();

		foreach($importCssFiles as $importCssFile) {

			$name = md5($importCssFile);

			if(in_array($name, self::$importedCssFiles) === false) {

				// CSS Datei Inline einbinden wenn es im konfigurierten Limit liegt
				if(self::$moduleCounter <= (int) $settings['moduleProcessor']['cssModuleInlineLimit']) {

					$inlineResourceService->addCss($importCssFile, [
						'useMinifyOnProduction' => $settings['moduleProcessor']['cssUseMinifyOnProduction']
					]);

				} else {
					$this->getPageRenderer()->addCssFile($importCssFile);
				}

				// in die bereits importierte Liste mit aufnehmen, damit Dateien nicht doppelt eingebunden werden
				self::$importedCssFiles[] = $name;
			}
		}
	}

	/**
	 * @param ContentObjectRenderer $cObj The data of the content element or page
	 * @param array $contentObjectConfiguration The configuration of Content Object
	 * @param array $processorConfiguration The configuration of this processor
	 * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
	 * @return array the processed data as key/value store
	 */
	public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData) {
		return $processedData;
	}
}