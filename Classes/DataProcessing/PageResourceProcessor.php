<?php

namespace Ps\Xo\DataProcessing;

use Ps\Xo\Service\InlineResourceService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Sorgt fuer die richtige Auswahl des Seitenlayout, je Backendeinstellung (page -> layout)
 */
class PageResourceProcessor extends AbstractProcessor implements DataProcessorInterface {

	/**
	 * Parst die Inhalte aller verknupeften Inhaltselemente
	 *
	 * @param ContentObjectRenderer $cObject The data of the content element or page
	 * @param array $contentObjectConfiguration The configuration of Content Object
	 * @param array $processorConfiguration The configuration of this processor
	 * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
	 * @return array the processed data as key/value store
	 */
	public function process(ContentObjectRenderer $cObject, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)	{

		if(isset($processorConfiguration['inlineCss.']) === true) {
			$this->processInlineCss($processorConfiguration['inlineCss.']);
		}

		if(isset($processorConfiguration['inlineJs.']) === true) {
			$this->processInlineJs($processorConfiguration['inlineJs.']);
		}
 
		return $processedData;
	}

	/**
	 * @param array $default
	 * @param array $overwrite
	 * @return array
	 */
	protected function getMergedOptions(array $default, array $overwrite): array {
		$options = $default;

		if(isset($overwrite['compress']) === true) {
			$options['compress'] = (boolean) $overwrite['compress'];
		}

		if(isset($overwrite['forceOnTop']) === true) {
			$options['forceOnTop'] = (boolean) $overwrite['forceOnTop'];
		}

		if(isset($overwrite['useMinifyOnProduction']) === true) {
			$options['useMinifyOnProduction'] = (boolean) $overwrite['useMinifyOnProduction'];
		}

		return $options;
	}

	/**
	 * @param array $resourceConfiguration
	 * @return void
	 */
	protected function processInlineCss($resourceConfiguration) {

		/** @var InlineResourceService $inlineResourceService */
		$inlineResourceService = GeneralUtility::makeInstance(InlineResourceService::class);

		$defaultOptions = [
			'compress' => true,
			'forceOnTop' => false,
			'useMinifyOnProduction' => true
		];

		foreach($resourceConfiguration as $key => $value) {

			// Keys mit . ist Optionen zu einer Datei
			if(strpos($key, '.') === false) {
				$file = $value;
				$options = $defaultOptions;

				// es gibt uebergebene Optionen zu dieser Resource
				if(isset($resourceConfiguration[$key . '.']) === true) {
					$options = $this->getMergedOptions($defaultOptions, $resourceConfiguration[$key . '.']);
				}

				$inlineResourceService->addCss($file, $options);
			}
		}
	}

	/**
	 * @param array $resourceConfiguration
	 * @return void
	 */
	protected function processInlineJs($resourceConfiguration) {

		/** @var InlineResourceService $inlineResourceService */
		$inlineResourceService = GeneralUtility::makeInstance(InlineResourceService::class);

		$defaultOptions = [
			'compress' => true,
			'forceOnTop' => false,
			'useMinifyOnProduction' => true
		];

		foreach($resourceConfiguration as $key => $value) {

			// Keys mit . ist Optionen zu einer Datei
			if(strpos($key, '.') === false) {
				$file = $value;
				$options = $defaultOptions;

				// es gibt uebergebene Optionen zu dieser Resource
				if(isset($resourceConfiguration[$key . '.']) === true) {
					$options = $this->getMergedOptions($defaultOptions, $resourceConfiguration[$key . '.']);
				}

				$inlineResourceService->addJs($file, $options);
			}
		}
	}
}
