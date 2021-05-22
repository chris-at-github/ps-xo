<?php

namespace Ps\Xo\ViewHelpers\JsonLd;

use Ps\Xo\Domain\Model\OpeningHours;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Christian Pschorr <pschorr.christian@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Json+Ld view helper parent class
 */
class AddressViewHelper extends AbstractJsonLdViewHelper {

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		$this->registerUniversalTagAttributes();
		$this->registerArgument('address', 'object', 'Object instance of \Ps\Xo\Domain\Model\Address', true);
	}

	/**
	 * @return \Ps\Xo\Domain\Model\Address
	 */
	protected function getAddress() {
		return $this->arguments['address'];
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileReference
	 * @return string
	 */
	protected function getImage($fileReference) {

		/* @var \TYPO3\CMS\Core\Resource\FileReference $image */
		$image = $fileReference->getOriginalResource();
		return \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $image->getPublicUrl();
	}

	/**
	 * @param array $address
	 * @return array
	 */
	protected function getPostalAddress(array $address): array {
		return [
			'@type' => 'PostalAddress',
			'addressLocality' => $address['zip'] . ' ' . $address['city'],
			'addressCountry' => $address['country'],
			'streetAddress' => $address['address']
		];
	}

	/**
	 * @param array $coordinates
	 * @return array
	 */
	protected function getGeoCoordinates(array $coordinates): array {
		return [
			'@type' => 'GeoCoordinates',
			'latitude' => $coordinates['latitude'],
			'longitude' => $coordinates['longitude']
		];
	}
}