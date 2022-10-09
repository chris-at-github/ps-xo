<?php

namespace Ps\Xo\DataProcessing;

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
		$framePrintClass = $this->getFramePrintClass($processedData, $processorConfiguration, $cObject);

		if(empty($frameTypeClass) === false) {
			$frameClasses[] = $frameTypeClass;
		}

		if(empty($frameOuterClass) === false) {
			$frameClasses[] = $frameOuterClass;
		}

		if(empty($framePrintClass) === false) {
			$frameClasses[] = $framePrintClass;
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
			['/[_\s]/', '/[-]+/', '/xo-/', '/xna-/', '/ce-/', '/ps14-/'],
			['-', '-', '', '', ''],
			strtolower(trim($frameType))
		);

		// mit einheitlichen Prefix versehen
		$frameType = 'ce-' . $frameType;

		// StdWrap auf $frameType anwenden
		if(isset($processorConfiguration['frameTypeClass.']) === true) {
			$frameType = $cObject->stdWrap($frameType, $processorConfiguration['frameTypeClass.']);
		}

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
		if(empty($processedData['data']['tx_xo_parent']) === false) {
			return '';
		}

		if($processedData['data']['colPos'] >= 4000) {
			return '';
		}

		return 'ce-frame--outer';
	}

	/**
	 * CSS Klassen zur Drucksteuerung
	 *
	 * @param array $processedData Daten des Contentelements aus der Datenbank
	 * @param array $processorConfiguration Konfiguration aus Typoscript
	 * @param ContentObjectRenderer $cObject
	 * @return string
	 */
	protected function getFramePrintClass($processedData, $processorConfiguration, $cObject) {
		$printClass = '';

		if(empty($processedData['data']['tx_xo_print_break']) === false) {
			$printClass .= ' print--break-' .  $processedData['data']['tx_xo_print_break'];
		}

		if(empty($processedData['data']['tx_xo_print_visibility']) === false) {
			$printClass .= ' print--visibility-' .  $processedData['data']['tx_xo_print_visibility'];
		}

		return trim($printClass);
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

	/**
	 * Auswertung der gesetzten Ueberschrift entweder direkt aus Header Type (Layout) als Darstellung im Style von ...
	 *
	 * @param array
	 * @return int
	 */
	protected function getHeaderClass($processedData) {
		$headerClass = (int) $processedData['data']['header_layout'];

		if(empty($processedData['data']['tx_xo_header_class']) === false) {
			$headerClass = (int) $processedData['data']['tx_xo_header_class'];
		}

		return $headerClass;
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

		// Fuege alle Klassen zusammen -> wird nur als String im Layout ausgegeben
		$processedData['data']['frame_classes'] = implode(' ', $this->getFrameClasses($processedData, $processorConfiguration, $cObject));

		// Abstandsklassen zu einem einheitlichen Zustand normalisieren
		$processedData = $this->normalizeSpaceClasses($processedData);

		// Header Klasse
		$processedData['data']['header_class'] = $this->getHeaderClass($processedData);

		return $processedData;
	}
}