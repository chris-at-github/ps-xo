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
	 * @var string
	 */
	protected $abstract;

	/**
	 * Categories
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ps\Xo\Domain\Model\Category>
	 */
	protected $categories;

	/**
	 * @var boolean
	 */
	protected $noLink = false;

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

	/**
	 * @return string
	 */
	public function getAbstract(): ?string {
		return $this->abstract;
	}

	/**
	 * @param string $abstract
	 */
	public function setAbstract(string $abstract): void {
		$this->abstract = $abstract;
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getCategories(): \TYPO3\CMS\Extbase\Persistence\ObjectStorage {
		return $this->categories;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories
	 */
	public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories): void {
		$this->categories = $categories;
	}

	/**
	 * @return bool
	 */
	public function isNoLink(): bool {
		return $this->noLink;
	}

	/**
	 * @param bool $noLink
	 */
	public function setNoLink(bool $noLink): void {
		$this->noLink = $noLink;
	}

	/**
	 * @return boolean
	 */
	public function isLinkable(): bool {
		if($this->isNoLink() === true) {
			return false;
		}

		return true;
	}
}