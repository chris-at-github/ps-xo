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
}
