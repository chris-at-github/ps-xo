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
class LocalBusinessViewHelper extends AddressViewHelper {

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('mainOpeningHoursCategory', 'int', 'Main uid for category for opening hours', false, 0);
	}

	/**
	 * @return string
	 */
	public function render() {

		// Global data
		$this->data = [
			'@context' => 'http://schema.org',
			'@type' => 'LocalBusiness',
			'@id' => 'LocalBusiness' . $this->getAddress()->getUid()
		];

		if(empty($this->getAddress()->getName()) === false) {
			$this->data['name'] = $this->getAddress()->getName();
		}

		if(empty($this->getAddress()->getDescription()) === false) {
			$this->data['description'] = $this->getAddress()->getName();
		}

		// Image
		if($this->getAddress()->getImage()->count() !== 0) {
			$this->data['image'] = $this->getImage($this->getAddress()->getImage()->current());
		}

		if(empty($this->getAddress()->getPhone()) === false) {
			$this->data['telephone'] = $this->getAddress()->getPhone();
		}

		if(empty($this->getAddress()->getEmail()) === false) {
			$this->data['email'] = $this->getAddress()->getEmail();
		}

		// Address
		$this->data['address'] = $this->getPostalAddress([
			'zip' => $this->getAddress()->getZip(),
			'city' => $this->getAddress()->getCity(),
			'country' => $this->getAddress()->getCountry(),
			'address' => $this->getAddress()->getAddress()
		]);

		// Geo
		if(empty($this->getAddress()->getLatitude()) === false && empty($this->getAddress()->getLongitude()) === false) {
			$this->data['geo'] = $this->getGeoCoordinates([
				'latitude' => $this->getAddress()->getLatitude(),
				'longitude' => $this->getAddress()->getLongitude()
			]);
		}

		// Opening Hours
		// @see: https://developers.google.com/search/docs/data-types/local-business?hl=de
		if(empty($this->getAddress()->getOpeningHours()) === false) {
			$this->data['openingHoursSpecification'] = [];

			/** @var OpeningHours $openingHours */
			foreach($this->getAddress()->getOpeningHours() as $openingHours) {

				$category = 0;
				if($openingHours->getCategory() !== null) {
					$category = $openingHours->getCategory()->getUid();
				}

				if((int) $this->arguments['mainOpeningHoursCategory'] !== $category) {
					continue;
				}

				$specification = [
					'@type' => 'OpeningHoursSpecification',
					'opens' => $openingHours->getOpenAt()->format('H:i'),
          'closes' => $openingHours->getCloseAt()->format('H:i'),
				];

				// die Keys entsprechen den englischen Wochentagen -> die Values waeren die Uebersetzungen
				$daysOfWeek = array_map(function($day) {
					return ucfirst($day);
				}, array_keys($openingHours->getDaysListing()));

				// bei nur einem ausgewaehlten Tag soll nur ein String uebergeben werden
				if(count($daysOfWeek) === 1) {
					$specification['dayOfWeek'] = $daysOfWeek[0];

				} else {
					$specification['dayOfWeek'] = $daysOfWeek;
				}

				$this->data['openingHoursSpecification'][] = $specification;
			}
		}

		return parent::render();
	}
}