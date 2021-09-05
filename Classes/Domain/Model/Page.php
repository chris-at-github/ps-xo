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
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
	 */
	protected $media = null;

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

	/**
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	public function getMedia(): ?\TYPO3\CMS\Extbase\Domain\Model\FileReference {
		return $this->media;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $media
	 */
	public function setMedia(?\TYPO3\CMS\Extbase\Domain\Model\FileReference $media): void {
		$this->media = $media;
	}
}