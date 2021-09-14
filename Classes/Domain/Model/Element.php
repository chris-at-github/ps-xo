<?php

namespace Ps\Xo\Domain\Model;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * The domain model of a Address
 *
 * @entity
 */
class Element extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

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
	protected $description;

	/**
	 * Categories
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ps\Xo\Domain\Model\Category>
	 */
	protected $categories;

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
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription(string $description): void {
		$this->description = $description;
	}
}