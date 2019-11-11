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
	 * Parst die Inhalte aller verknupeften Inhaltselemente
	 *
	 * @param ContentObjectRenderer $cObject The data of the content element or page
	 * @param array $contentObjectConfiguration The configuration of Content Object
	 * @param array $processorConfiguration The configuration of this processor
	 * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
	 * @return array the processed data as key/value store
	 */
	public function process(ContentObjectRenderer $cObject, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)	{
		if(isset($processorConfiguration['mapping.'][$processedData['data']['layout']]) === true) {
			$processedData[$processorConfiguration['as']] = $processorConfiguration['mapping.'][$processedData['data']['layout']];
	
		} else {
			$processedData[$processorConfiguration['as']] = $processorConfiguration['default'];
		}
 
		return $processedData;
	}
}
