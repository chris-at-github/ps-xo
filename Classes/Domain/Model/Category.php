<?php

namespace Ps\Xo\Domain\Model;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * The domain model of a Address
 *
 * @entity
 */
class Category extends \TYPO3\CMS\Extbase\Domain\Model\Category {

	/**
	 * @var string
	 */
	protected $link;

	/**
	 * @return string
	 */
	public function getLink(): string {
		return $this->link;
	}

	/**
	 * @param string $link
	 */
	public function setLink(string $link): void {
		$this->link = $link;
	}
}