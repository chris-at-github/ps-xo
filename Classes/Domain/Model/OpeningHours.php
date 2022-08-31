<?php
declare(strict_types=1);

namespace Ps\Xo\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * This file is part of the "XOX" Extension for TYPO3 CMS.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * (c) 2021
 * OpeningHours
 */
class OpeningHours extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var string
	 */
	protected $days = '';

	/**
	 * @var string
	 */
	protected $daysTitle = '';

	/**
	 * @var \DateTime
	 */
	protected $openAt = null;

	/**
	 * @var \DateTime
	 */
	protected $closeAt = null;

	/**
	 * @var \Ps\Xo\Domain\Model\Category
	 */
	protected $category = null;

	/**
	 * @return string $days
	 */
	public function getDays() {
		return $this->days;
	}

	/**
	 * @param string $days
	 * @return void
	 */
	public function setDays($days) {
		$this->days = $days;
	}

	/**
	 * @return \DateTime $openAt
	 */
	public function getOpenAt() {
		if($this->openAt !== null) {

			// TYPO3 speichert das Datum mit Timezone UTC ab
			// @see: https://www.zechendorf.com/blog/2017/extbase-probleme-im-tca-mit-zeitzone/
			return new \DateTime('@' . $this->openAt->format('U'), new \DateTimeZone('UTC'));
		}

		return $this->openAt;
	}

	/**
	 * @param \DateTime $openAt
	 * @return void
	 */
	public function setOpenAt(\DateTime $openAt) {
		$this->openAt = $openAt;
	}

	/**
	 * @return \DateTime $closeAt
	 */
	public function getCloseAt() {

		if($this->closeAt !== null) {

			// TYPO3 speichert das Datum mit Timezone UTC ab
			// @see: https://www.zechendorf.com/blog/2017/extbase-probleme-im-tca-mit-zeitzone/
			return new \DateTime('@' . $this->closeAt->format('U'), new \DateTimeZone('UTC'));
		}

		return $this->closeAt;
	}

	/**
	 * @param \DateTime $closeAt
	 * @return void
	 */
	public function setCloseAt(\DateTime $closeAt) {
		$this->closeAt = $closeAt;
	}

	/**
	 * @return array
	 */
	public function getDaysListing() {
		$days = GeneralUtility::trimExplode(',', $this->getDays());
		$listing = [];

		foreach($days as $day) {
			$listing[$day] = LocalizationUtility::translate('LLL:EXT:xo/Resources/Private/Language/locallang_frontend.xlf:tx_xo_date.' . $day, 'Xo');
		}

		return $listing;
	}

	/**
	 * @return string
	 */
	public function getDaysTitle(): string {
		return $this->daysTitle;
	}

	/**
	 * @param string $daysTitle
	 */
	public function setDaysTitle(string $daysTitle): void {
		$this->daysTitle = $daysTitle;
	}

	/**
	 * @return Category|null
	 */
	public function getCategory(): ?Category {
		return $this->category;
	}

	/**
	 * @param Category|null $category
	 */
	public function setCategory(?Category $category): void {
		$this->category = $category;
	}
}
