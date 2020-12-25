<?php

namespace Ps\Xo\Domain\Model;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * The domain model of a Address
 *
 * @entity
 */
class Page extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle(string $title): void {
		$this->title = $title;
	}
}