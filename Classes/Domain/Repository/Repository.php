<?php

namespace Ps\Xo\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * The repository for the domain model Pages
 */
class Repository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface
	 */
	protected $querySettings = null;

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param array $options
	 * @return array
	 */
	protected function getMatches($query, $options) {
		$matches = [];

		if(isset($options['records']) === true) {

			// bei Eingabe von festen IDs duerfen nur die IDs der Hauptsprache verwendet werden, Extbase kuemmert sich per
			// Overlay um die korrekte Uebersetzung
			// @see: https://docs.typo3.org/m/typo3/book-extbasefluid/master/en-us/9-CrosscuttingConcerns/1-localizing-and-internationalizing-an-extension.html#typo3-v9-and-higher
//			$query->getQuerySettings()->setRespectSysLanguage(false);
			$query->getQuerySettings()->setLanguageOverlayMode(true);

			// immer als Array auswerten
			if(is_array($options['records']) === false) {
				$options['records'] = [$options['records']];
			}

			$matches[] = $query->in('uid', $options['records']);
		}

		return $matches;
	}

	/**
	 * @param array $options
	 * @return QueryResultInterface
	 * @throws InvalidQueryException
	 */
	public function findAll($options = []) {
		$query = $this->createQuery();

		if($this->querySettings !== null) {
			$query->setQuerySettings($this->querySettings);
		}

		if(empty($matches = $this->getMatches($query, $options)) === false) {
			$query->matching($query->logicalAnd($matches));
		}

		return $query->execute();
	}

	/**
	 * @param array $options
	 * @return object
	 * @throws InvalidQueryException
	 */
	public function find($options = []) {
		return $this->findAll($options)->getFirst();
	}

	/**
	 * @param array $settings
	 * @return Repository
	 */
	public function setQuerySettings($settings) {
		if(empty($settings) === false) {
			$this->querySettings = $this->createQuery()->getQuerySettings();

			if(isset($settings['respectStoragePage']) === true) {
				$this->querySettings->setRespectStoragePage($settings['respectStoragePage']);
			}
		}

		return $this;
	}
}
