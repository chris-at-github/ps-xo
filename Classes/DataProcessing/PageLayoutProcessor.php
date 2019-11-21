<?php

namespace Ps\Xo\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Sorgt fuer die richtige Auswahl des Seitenlayout, je Backendeinstellung (page -> layout)
 */
class PageLayoutProcessor implements DataProcessorInterface {

	/**
	 * Object Manager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @return void
	 */
	public function __construct() {
		$this->objectManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
	}

	/**
	 * @param array $processorConfiguration The configuration of this processor
	 * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
	 * @return int
	 */
	protected function getLayout($processorConfiguration, $processedData) {
		$layout = $processedData['data']['layout'];

		// wenn Layout nicht direkt zugeordnet ist
		// wenn man sich nicht auf der obersten Ebene befindet
		// wenn die recursive Suche aktiv ist
		if(empty($layout) === true && $processedData['data']['pid'] !== 0 && (isset($processorConfiguration['recursive']) === true && (int) $processorConfiguration['recursive'] === 1)) {
			$rootline = $this->objectManager->get(\TYPO3\CMS\Core\Utility\RootlineUtility::class, $processedData['data']['pid'])->get();

			if(empty($rootline) === false) {
				foreach($rootline as $page) {
					if(empty($page['layout']) === false) {
						return (int) $page['layout'];
					}
				}
			}
		}

		return $layout;
	}

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
		$layout = $this->getLayout($processorConfiguration, $processedData);

		if(isset($processorConfiguration['mapping.'][$layout]) === true) {
			$processedData[$processorConfiguration['as']] = $processorConfiguration['mapping.'][$layout];
	
		} else {
			$processedData[$processorConfiguration['as']] = $processorConfiguration['default'];
		}
 
		return $processedData;
	}
}
