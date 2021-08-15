<?php

namespace Ps\Xo\Domain\Model;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use Ps\Xo\Enumeration\SchemaOrgType;

/**
 * The domain model of a Address
 *
 * @entity
 */
class Address extends \FriendsOfTYPO3\TtAddress\Domain\Model\Address {

	/**
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	protected $schemaOrgMedia;

	/**
	 * @var string
	 */
	protected $directors;

	/**
	 * @var string
	 */
	protected $commercialRegister;

	/**
	 * @var string
	 */
	protected $registeredOffice;

	/**
	 * @var string
	 */
	protected $vatNumber;

	/**
	 * @var string
	 */
	protected $openingHoursDescription = '';

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ps\Xo\Domain\Model\OpeningHours>
	 * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
	 * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
	 */
	protected $openingHours = null;

	/**
	 * @var string
	 */
	protected $instagram;

	/**
	 * @var string
	 */
	protected $youtube;

	/**
	 * __construct
	 */
	public function __construct() {
		parent::__construct();
		$this->initializeObject();
	}

	/**
	 * @return void
	 */
	public function initializeObject() {
		$this->openingHours = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	public function getSchemaOrgMedia() {
		return $this->schemaOrgMedia;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $schemaOrgMedia
	 */
	public function setSchemaOrgMedia(\TYPO3\CMS\Extbase\Domain\Model\FileReference $schemaOrgMedia) {
		$this->schemaOrgMedia = $schemaOrgMedia;
	}

	/**
	 * @return string
	 */
	public function getCommercialRegister(): string {
		return $this->commercialRegister;
	}

	/**
	 * @param string $commercialRegister
	 */
	public function setCommercialRegister(string $commercialRegister): void {
		$this->commercialRegister = $commercialRegister;
	}

	/**
	 * @return string
	 */
	public function getDirectors(): string {
		return $this->directors;
	}

	/**
	 * @param string $directors
	 */
	public function setDirectors(string $directors): void {
		$this->directors = $directors;
	}

	/**
	 * @return string
	 */
	public function getRegisteredOffice(): string {
		return $this->registeredOffice;
	}

	/**
	 * @param string $registeredOffice
	 */
	public function setRegisteredOffice(string $registeredOffice): void {
		$this->registeredOffice = $registeredOffice;
	}

	/**
	 * @return string
	 */
	public function getVatNumber(): string {
		return $this->vatNumber;
	}

	/**
	 * @param string $vatNumber
	 */
	public function setVatNumber(string $vatNumber): void {
		$this->vatNumber = $vatNumber;
	}

	/**
	 * @return string openingHoursDescription
	 */
	public function getOpeningHoursDescription() {
		return $this->openingHoursDescription;
	}

	/**
	 * @param string $openingHoursDescription
	 * @return void
	 */
	public function setOpeningHoursDescription($openingHoursDescription) {
		$this->openingHoursDescription = $openingHoursDescription;
	}

	/**
	 * @param \Ps\Xo\Domain\Model\OpeningHours $openingHour
	 * @return void
	 */
	public function addOpeningHour(\Ps\Xo\Domain\Model\OpeningHours $openingHour) {
		$this->openingHours->attach($openingHour);
	}

	/**
	 * @param \Ps\Xo\Domain\Model\OpeningHours $openingHour
	 * @return void
	 */
	public function removeOpeningHour(\Ps\Xo\Domain\Model\OpeningHours $openingHour) {
		$this->openingHours->detach($openingHour);
	}

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ps\Xo\Domain\Model\OpeningHours> openingHours
	 */
	public function getOpeningHours() {
		return $this->openingHours;
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Ps\Xo\Domain\Model\OpeningHours> $openingHours
	 * @return void
	 */
	public function setOpeningHours(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $openingHours) {
		$this->openingHours = $openingHours;
	}

	/**
	 * @return string
	 */
	public function getInstagram(): string {
		return $this->instagram;
	}

	/**
	 * @param string $instagram
	 */
	public function setInstagram(string $instagram): void {
		$this->instagram = $instagram;
	}

	/**
	 * @return string
	 */
	public function getYoutube(): string {
		return $this->youtube;
	}

	/**
	 * @param string $youtube
	 */
	public function setYoutube(string $youtube): void {
		$this->youtube = $youtube;
	}
}
