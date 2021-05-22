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
class PersonViewHelper extends \Ps\Xo\ViewHelpers\JsonLd\AddressViewHelper {

	/**
	 * @return string
	 */
	public function render() {

		// Global data
		$this->data = [
			'@context' => 'http://schema.org',
			'@type' => 'Person',
			'@id' => 'Person' . $this->getAddress()->getUid()
		];

		if(empty($this->getAddress()->getName()) === false) {
			$this->data['name'] = $this->getAddress()->getName();
		}

		if(empty($this->getAddress()->getPosition()) === false) {
			$this->data['jobTitle'] = $this->getAddress()->getPosition();
		}

		// Image
		if($this->getAddress()->getImage()->count() !== 0) {

			/* @var \TYPO3\CMS\Core\Resource\FileReference $image */
			$image = $this->getAddress()->getImage()->current()->getOriginalResource();
			$this->data['image'] = \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL') . $image->getPublicUrl();
		}

		if(empty($this->getAddress()->getPhone()) === false) {
			$this->data['telephone'] = $this->getAddress()->getPhone();
		}

		if(empty($this->getAddress()->getEmail()) === false) {
			$this->data['email'] = $this->getAddress()->getEmail();
		}

		return parent::render();
	}
}