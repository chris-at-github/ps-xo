<?php

namespace Ps\Xo\Domain\Repository;

/**
 * The repository for the domain model sys_category
 */
class CategoryRepository extends Repository {

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param array $options
	 * @return array
	 */
	protected function getMatches($query, $options) {
		$matches = parent::getMatches($query, $options);

		if(isset($options['parent']) === true) {
			$matches['parent'] = $query->equals('parent', (int) $options['parent']);
		}

		return $matches;
	}
}
