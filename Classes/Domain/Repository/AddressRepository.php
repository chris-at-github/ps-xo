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
	 * @param array $options
	 * @return QueryResultInterface
	 * @throws InvalidQueryException
	 */
	public function findAll($options = []) {
		$query = $this->createQuery();
		$matches = [];

		if(isset($options['content']) === true) {
			$matches[] = $query->equals('txXoContent', (int) $options['content']);
		}

		if(isset($options['records']) === true) {

			// bei Eingabe von festen IDs duerfen nur die IDs der Hauptsprache verwendet werden, Extbase kuemmert sich per
			// Overlay um die korrekte Uebersetzung
			$query->getQuerySettings()->setRespectSysLanguage(false);

			$matches[] = $query->in('uid', $options['records']);
		}

		if(empty($matches) === false) {
			$query->matching($query->logicalAnd($matches));
		}

		return $query->execute();
	}
}
