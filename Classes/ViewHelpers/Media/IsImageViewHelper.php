<?php

namespace Ps\Xo\ViewHelpers\Media;

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

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Ueberprueft ob die uebergebene FileReference ein Bild ist
 */
class IsImageViewHelper extends AbstractConditionViewHelper {

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		parent::initializeArguments();

		$this->registerArgument('file', 'object', 'File oder FileRefernce welche geprueft werden soll', true);
	}

	/**
	 * Ein Bild?
	 *
	 * @param array $arguments ViewHelper arguments to evaluate the condition for this ViewHelper, allows for flexiblity in overriding this method.
	 * @return bool
	 */
	protected static function evaluateCondition($arguments = null) {

		if(is_object($arguments['file']) === false) {
			return false;
		}

		if(($arguments['file'] instanceof File) === false && ($arguments['file'] instanceof FileReference) === false) {
			return false;
		}

		$properties = $arguments['file']->getProperties();

		if(strtolower($properties['extension']) === 'jpg' || strtolower($properties['extension']) === 'png' || strtolower($properties['extension']) === 'gif') {
			return true;
		}

		return false;
	}
}
