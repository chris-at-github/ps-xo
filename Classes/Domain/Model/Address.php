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
}
