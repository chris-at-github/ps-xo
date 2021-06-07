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
	static protected $importedFiles = [];

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
	 * @var string[]
	 */
	protected $importJsFiles = [];

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

		// JS Dateien aus $importJsFiles verarbeiten
		$this->addImportJsFiles($this->importJsFiles);
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
	 * @param array $configuration
	 * @return void
	 */
	public function addFilesByProcessorConfiguration($configuration) {

		// CSS Dateien importieren
		if(empty(isset($configuration['importCss.'])) === false) {

			// anhand des Keys sortieren -> um eine gewollte Reihenfolge abzudecken
			ksort($configuration['importCss.']);

			$this->addImportCssFiles($configuration['importCss.']);
		}

		// JS Dateien importieren
		if(empty(isset($configuration['importJs.'])) === false) {

			// anhand des Keys sortieren -> um eine gewollte Reihenfolge abzudecken
			ksort($configuration['importJs.']);

			$this->addImportJsFiles($configuration['importJs.']);
		}
	}

	/**
	 * Fuegt die Css Dateien im Head hinzu
	 *
	 * @param string[] $importCssFiles
	 * @return void
	 */
	protected function addImportCssFiles($importCssFiles) {

		/** @var InlineResourceService $inlineResourceService */
		$inlineResourceService = GeneralUtility::makeInstance(InlineResourceService::class);
		$settings = $this->getSettings();

		foreach($importCssFiles as $importCssFile) {

			$name = md5($importCssFile);

			if(in_array($name, self::$importedFiles) === false) {

				// CSS Datei Inline einbinden wenn es im konfigurierten Limit liegt
				if(self::$moduleCounter <= (int) $settings['moduleProcessor']['cssModuleInlineLimit']) {

					$inlineResourceService->addCss($importCssFile, [
						'useMinifyOnProduction' => $settings['moduleProcessor']['cssUseMinifyOnProduction']
					]);

				} else {
					$this->getPageRenderer()->addCssFile($importCssFile);
				}

				// in die bereits importierte Liste mit aufnehmen, damit Dateien nicht doppelt eingebunden werden
				self::$importedFiles[] = $name;
			}
		}
	}

	/**
	 * Fuegt die Js Dateien der Seite hinzu
	 *
	 * @param string[] $importJsFiles
	 * @return void
	 */
	protected function addImportJsFiles($importJsFiles) {

		/** @var InlineResourceService $inlineResourceService */
		$inlineResourceService = GeneralUtility::makeInstance(InlineResourceService::class);
		$settings = $this->getSettings();

		foreach($importJsFiles as $importJsFile => $importJsFileOptions) {

			// wenn es kein Array ist, ist es der Pfad zu der Datei
			if(is_array($importJsFileOptions) === false) {
				$importJsFile = $importJsFileOptions;
				$importJsFileOptions = [];
			}

			$name = md5($importJsFile);

			if(in_array($name, self::$importedFiles) === false) {

				// CSS Datei Inline einbinden wenn es im konfigurierten Limit liegt
				if(self::$moduleCounter <= (int) $settings['moduleProcessor']['jsModuleInlineLimit']) {

					$inlineResourceService->addJs($importJsFile, [
						'useMinifyOnProduction' => $settings['moduleProcessor']['jsUseMinifyOnProduction']
					]);

				} else {

					$importJsFileOptions = array_merge([
						'type' => 'text/javascript',
						'compress' => true,
						'forceOnTop' => false,
						'allWrap' => '',
						'excludeFromConcatenation' => false,
						'splitChar' => '|',
						'async' => false,
						'integrity' => '',
						'defer' => false,
						'crossorigin' => ''
					], $importJsFileOptions);

					$this->getPageRenderer()->addJsFooterFile(
						$importJsFile,
						$importJsFileOptions['type'],
						$importJsFileOptions['compress'],
						$importJsFileOptions['forceOnTop'],
						$importJsFileOptions['allWrap'],
						$importJsFileOptions['excludeFromConcatenation'],
						$importJsFileOptions['splitChar'],
						$importJsFileOptions['async'],
						$importJsFileOptions['integrity'],
						$importJsFileOptions['defer'],
						$importJsFileOptions['crossorigin']
					);
				}

				// in die bereits importierte Liste mit aufnehmen, damit Dateien nicht doppelt eingebunden werden
				self::$importedFiles[] = $name;
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

		// CSS- und JS-Dateien anhand der Processor Configuration laden
		$this->addFilesByProcessorConfiguration($processorConfiguration);

		return $processedData;
	}
}