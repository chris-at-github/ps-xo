<?php

namespace Ps\Go\DataProcessing;

use Doctrine\Common\Util\Debug;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Standard ContentElementProcessor fuer alle MLL Inhaltselemente
 */
class ContentElementProcessor implements DataProcessorInterface {

	/**
	 * liefert ein Array mit den CSS-Klassen die dem aueßeren DIV zugeordnet werden
	 *
	 * @param array $processedData Daten des Contentelements aus der Datenbank
	 * @param array $processorConfiguration Konfiguration aus Typoscript
	 * @param ContentObjectRenderer $cObject
	 * @return array
	 */
	protected function getFrameClasses(array $processedData, array $processorConfiguration, ContentObjectRenderer $cObject) {
		$frameClasses = [];
		$frameTypeClass = $this->getFrameTypeClass($processedData, $processorConfiguration, $cObject);
		$frameOuterClass = $this->getFrameOuterClass($processedData, $processorConfiguration, $cObject);

		if(empty($frameTypeClass) === false) {
			$frameClasses[] = $frameTypeClass;
		}

		if(empty($frameOuterClass) === false) {
			$frameClasses[] = $frameOuterClass;
		}

		return $frameClasses;
	}

	/**
	 * Erstellt eine Klasse anhand des CTypes
	 *
	 * @param array $processedData Daten des Contentelements aus der Datenbank
	 * @param array $processorConfiguration Konfiguration aus Typoscript
	 * @param ContentObjectRenderer $cObject
	 * @return string
	 */
	protected function getFrameTypeClass($processedData, $processorConfiguration, $cObject) {
		$frameType = $processedData['data']['CType'];

		// Plugins
		if($frameType === 'list' && empty($processedData['data']['list_type']) === false) {
			$frameType = $processedData['data']['list_type'];
		}

		// Bezeichner normalisieren
		// _ -> -
		// -- -> -
		$frameType = preg_replace(
			['/[_\s]/', '/[-]+/'],
			['-', '-'],
			strtolower(trim($frameType))
		);

		// StdWrap auf $frameType anwenden
		if(isset($processorConfiguration['frameTypeClass.']) === true) {
			$frameType = $cObject->stdWrap($frameType, $processorConfiguration['frameTypeClass.']);
		}


		// mit einheitlichen Prefix versehen
		$frameType = 'ce-frame--type-' . $frameType;

		return $frameType;
	}


	/**
	 * Liefert eine Klasse falls das Element sich auf Root-Ebene befindet. Bei verschachtelten Elementen entfaellt diese
	 * Klasse
	 *
	 * @param array $processedData Daten des Contentelements aus der Datenbank
	 * @param array $processorConfiguration Konfiguration aus Typoscript
	 * @param ContentObjectRenderer $cObject
	 * @return string
	 */
	protected function getFrameOuterClass($processedData, $processorConfiguration, $cObject) {
		return 'ce-frame--outer';
	}


	/**
	 * Fuer die Einstellung none in das Abstandseinstellungen wird normalerweise nicht ausgegeben. Fuer die korrekte
	 * Auswertung im CSS werden auch die space-*-none Klassen im Code benoetigt
	 *
	 * @param array $processedData
	 * @return array $processedData
	 */
	protected function normalizeSpaceClasses($processedData) {

		if(empty($processedData['data']['space_before_class']) === true) {
			$processedData['data']['space_before_class'] = 'none';
		}

		if(empty($processedData['data']['space_after_class']) === true) {
			$processedData['data']['space_after_class'] = 'none';
		}

		return $processedData;
	}

//	/**
//	 *  Get the header and or header-style class  and add class to <header> accordingly
//	 * @param array
//	 * @return  string
//	 */
//	protected function getHeaderClasses($processedData) {
////		only header [1-5] available
//		$headerAvailable = array(1, 2, 3, 4, 5);
//		$headerClasses = '';
//		if($processedData["data"]["tx_datamints_mll_header_style"]) {
//			$headerClasses .= 'header-layout-' . $processedData["data"]["tx_datamints_mll_header_style"];
//		} elseif(in_array($processedData["data"]["header_layout"], $headerAvailable)) {
//			$headerClasses .= 'header-layout-' . $processedData["data"]["header_layout"];
//		}
//
//		return $headerClasses;
//	}

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

		// Fuege alle Klassen zusammen -> wird nur als String im Layout ausgegeben
		$processedData['data']['frame_classes'] = implode(' ', $this->getFrameClasses($processedData, $processorConfiguration, $cObject));

		// Abstandsklassen zu einem einheitlichen Zustand normalisieren
		$processedData = $this->normalizeSpaceClasses($processedData);

//		// Header Klasse
//		$processedData['data']['header_class'] = $this->getHeaderClasses($processedData);

		return $processedData;
	}
}