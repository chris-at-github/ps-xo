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
	 * @var int
	 */
	protected $schemaOrgType;

	/**
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
	 */
	protected $schemaOrgMedia;

	/**
	 * @return int
	 */
	public function getSchemaOrgType() {
		return $this->schemaOrgType;
	}

	/**
	 * @param int $schemaOrgType
	 */
	public function setSchemaOrgType($schemaOrgType) {
		$this->schemaOrgType = $schemaOrgType;
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
	public function getSchemaOrgTypeSectionName() {
		$enumeration = SchemaOrgType::cast($this->getSchemaOrgType());
		$name = SchemaOrgType::getHumanReadableName($this->getSchemaOrgType());

		if($enumeration->equals(SchemaOrgType::NONE) === true) {
			$name = 'Default';
		}

		return str_replace(' ', '', $name);
	}
}
