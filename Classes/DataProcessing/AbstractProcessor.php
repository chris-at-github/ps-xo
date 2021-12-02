<?php

namespace Ps\Xo\DataProcessing;

use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * AddressProcessor fuer TtAddress Datensaetze, die innerhalb von Content Elementen (FSCEs) verknuepft sind. Diese werden
 * im Feld tx_xo_content innerhalb der Tabelle tt_address mit der UID des Content Elements versehen
 */
abstract class AbstractProcessor implements DataProcessorInterface {

	/**
	 * @param ContentObjectRenderer $cObj The data of the content element or page
	 * @param array $processorConfiguration The configuration of this processor
	 * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
	 * @return array|null flexform data
	 */
	public function getFlexFormData(ContentObjectRenderer $cObject, array $processorConfiguration, array $processedData) {

		// The field name to process
		$fieldName = $cObject->stdWrapValue('fieldName', $processorConfiguration);
		if(empty($fieldName)) {
			$fieldName = 'pi_flexform';
		}

		if(!$processedData['data'][$fieldName]) {
			return null;
		}

		// Process Flexform
		$originalValue = $processedData['data'][$fieldName];
		if(is_string($originalValue) === false) {
			return null;
		}

		/** @var FlexFormService $flexFormService */
		$flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
		$flexformData = $flexFormService->convertFlexFormContentToArray($originalValue);

		return $flexformData;
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
	public function process(ContentObjectRenderer $cObject, array $contentObjectConfiguration, array $processorConfiguration, array $processedData) {
		return $processedData;
	}
}