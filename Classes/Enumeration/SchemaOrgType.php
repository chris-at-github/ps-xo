<?php

namespace Ps\Xo\Enumeration;

class SchemaOrgType extends \TYPO3\CMS\Core\Type\Enumeration {

	/**
	 * @var int
	 */
	const __default = self::NONE;

	/**
	 * @var int
	 */
	const NONE = 0;

	/**
	 * @var int
	 */
	const LOCAL_BUSINESS = 1;
}