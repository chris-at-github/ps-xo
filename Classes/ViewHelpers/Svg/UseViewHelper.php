<?php

namespace Cext\Play\ViewHelpers\Svg;

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

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * View helper to render a SVG icon inline.
 */
class UseViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'svg';

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		$this->registerUniversalTagAttributes();
		$this->registerArgument('preserveAspectRatio', 'string', 'Alignment and scaling options from the SVG standard', false, null);
	}

	/**
	 * Erstellt end SVG Tag mit einem Use-Tag referenziert auf das Icon-Quelle
	 * @see: https://css-tricks.com/svg-use-with-external-reference-take-2/
	 *
	 * @param string $name Name used for use tag and for class name
	 * @return string
	 */
	protected function render($name) {
		return $this->tag->render();
	}

	/**
	 * Rendert den Use-Tag
	 *
	 * @param string $name
	 * @return string
	 */
	protected function renderUseTag($name) {
		return '<use xlink:href="#sprite-' . $name . '"></use>';
	}
}
