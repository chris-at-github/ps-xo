<?php

namespace Ps\Xo\DataProcessing;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class NavigationContentProcessor extends ContentProcessor implements DataProcessorInterface {

	/**
	 * @param int $pageUid
	 * @return array
	 */
	protected function getContentUids(int $pageUid) {
		$contentUids = [];

		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_xo_pages_content_mm')->createQueryBuilder();
		$query = $queryBuilder->select('*')
			->from('tx_xo_pages_content_mm')
			->where(
				$queryBuilder->expr()->eq('uid_local', $queryBuilder->createNamedParameter($pageUid, \PDO::PARAM_INT))
			)
			->orderBy('sorting', 'asc');

		$statement = $query->execute();

		while($row = $statement->fetch()) {
			$contentUids[] = (int) $row['uid_foreign'];
		}

		return $contentUids;
	}

	/**
	 * Fetches records from the database as an array
	 *
	 * @param ContentObjectRenderer $contentObject The data of the content element or page
	 * @param array $contentObjectConfiguration The configuration of Content Object
	 * @param array $processorConfiguration The configuration of this processor
	 * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
	 *
	 * @return array the processed data as key/value store
	 */
	public function process(ContentObjectRenderer $contentObject, array $contentObjectConfiguration, array $processorConfiguration, array $processedData): array {

		$contentUids = $this->getContentUids((int) $processedData['data']['uid']);

		if(empty($contentUids) === false) {
			$processorConfiguration['select.'] = [
				'uidInList' => implode(', ', $contentUids),
				'pidInList' => 0
			];

			$processedData = parent::process($contentObject, $contentObjectConfiguration, $processorConfiguration, $processedData);

		} else {
			$processedData[$processorConfiguration['as']] = '';
		}

		return $processedData;
	}
}
