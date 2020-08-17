<?php

namespace Ps\Xo\ViewHelpers\Style;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020 Christian Pschorr <pschorr.christian@gmail.com>
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
 * Bindet Inline Styles im Head ein
 */
class InlineCodeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * TYPO3's configuration manager
	 *
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		parent::initializeArguments();

		$this->registerArgument('name', 'string', 'Key for cssInline array', true);
		$this->registerArgument('code', 'string', 'Content for css code', true);
		$this->registerArgument('compress', 'boolean', 'Compress', false, true);
		$this->registerArgument('forceOnTop', 'boolean', 'Force on top', false);
	}

	/**
	 * Einbindung des Skript Tags ueber die globale Page Variable
	 *
	 * @return string
	 */
	protected function render() {

		/** @var \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer */
		$pageRenderer = $GLOBALS['TSFE']->pageRenderer;
		$pageRenderer->addCssInlineBlock(
			$this->arguments['name'],
			$this->arguments['code'],
			$this->arguments['compress'],
			$this->arguments['forceOnTop']);
	}
}