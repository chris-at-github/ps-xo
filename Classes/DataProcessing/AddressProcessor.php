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
	 * @param int $uid
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
	 */
	public function getRecords($uid) {
		return $this->objectManager->get(\Ps\Xo\Domain\Repository\AddressRepository::class)->findAll([
			'content' => $uid
		]);
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

		// nur weiter verarbeiten wenn mindestens ein Adressdatensatz vernuepft ist
		if((int) $processedData['data']['tx_xo_address'] !== 0) {
			$processedData['data']['addresses'] = $this->getRecords((int) $processedData['data']['uid']);
		}

		return $processedData;
	}
}