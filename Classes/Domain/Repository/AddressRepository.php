<?php

namespace Ps\Xo\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * The repository for the domain model Address
 */
class AddressRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * override the storagePid settings (do not use storagePid) of extbase
	 */
	public function initializeObject() {
		$this->defaultQuerySettings = $this->objectManager->get(Typo3QuerySettings::class);
		$this->defaultQuerySettings->setRespectStoragePage(false);
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param array $options
	 * @return array
	 */
	protected function getMatches($query, $options) {
		$matches = [];

		if(isset($options['content']) === true) {
			$matches[] = $query->equals('txXoContent', (int) $options['content']);
		}

		if(isset($options['records']) === true) {

			// bei Eingabe von festen IDs duerfen nur die IDs der Hauptsprache verwendet werden, Extbase kuemmert sich per
			// Overlay um die korrekte Uebersetzung
			$query->getQuerySettings()->setRespectSysLanguage(false);

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
}
