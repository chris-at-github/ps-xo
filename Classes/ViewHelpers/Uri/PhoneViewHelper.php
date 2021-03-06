<?php

namespace Ps\Xo\ViewHelpers\Uri;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Christian Pschorr <pschorr.christian@gmail.com>
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
 * Returns a phone based uri with tel: protocol
 */
class PhoneViewHelper extends AbstractViewHelper {

	/**
	 * @var bool
	 */
	protected $escapeOutput = false;

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('phone', 'string', 'phone number', true);
		$this->registerArgument('protocol', 'string', 'tel:', false, 'tel:');
	}

	/**
	 * Entfernt alle unnoetigen Zeichen aus der Telefonnummer und fuegt das tel: Protokoll hinzu
	 *
	 * @return string
	 */
	protected function render() {
		return trim($this->arguments['protocol']) . preg_replace('/[^\d\+]/', '', trim($this->arguments['phone']));
	}
}
