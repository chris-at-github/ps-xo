<?php

namespace Ps\Xo\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * AddressProcessor fuer TtAddress Datensaetze, die innerhalb von Content Elementen (FSCEs) verknuepft sind. Diese werden
 * im Feld tx_xo_content innerhalb der Tabelle tt_address mit der UID des Content Elements versehen
 */
class AddressProcessor implements DataProcessorInterface {

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
	 * @param array $options
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
	 */
	public function getRecords($options) {
		return $this->objectManager->get(\Ps\Xo\Domain\Repository\AddressRepository::class)->setQuerySettings(['respectStoragePage' => false])->findAll($options);
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

		if(isset($processorConfiguration['as']) === false) {
			$processorConfiguration['as'] = 'address';
		}

		// Sammeln von Bedingungen fuer die Repository-Abfrage
		$options = [];

		if(isset($processorConfiguration['records']) === true) {

			// direkter Zahlenwert
			if(is_numeric($processorConfiguration['records']) === true) {
				$options['records'] = (int) $processorConfiguration['records'];

			// TypoScript
			} elseif(isset($processorConfiguration['records.']) === true) {
				// @todo: Auswertung TypoScript (siehe typo3/sysext/frontend/Classes/DataProcessing/FilesProcessor.php)
			}
		}

		$processedData[$processorConfiguration['as']] = $this->getRecords($options);

		return $processedData;
	}
}