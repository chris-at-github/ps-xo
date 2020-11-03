<?php

namespace Ps\Xo\ViewHelpers\Css;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Extbase\Annotation\Inject;

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

/**
 * Bindet Inline Styles im Head ueber eine Datei ein
 */
class InlineFileViewHelper extends AbstractViewHelper {

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		parent::initializeArguments();

		$this->registerArgument('name', 'string', 'Key for cssInline array', false);
		$this->registerArgument('file', 'string', 'Content for css code', true);
		$this->registerArgument('compress', 'boolean', 'Compress', false, true);
		$this->registerArgument('forceOnTop', 'boolean', 'Force on top', false);
	}

	/**
	 * Einbindung des Skript Tags ueber die globale Page Variable
	 *
	 * @return void
	 */
	protected function render() {

		$path = $this->resolvePath(trim($this->arguments['file']));
		$output = file_get_contents($path);

		if(empty($this->arguments['name']) === true) {
			$this->arguments['name'] = md5(trim($this->arguments['file']));
		}

		/** @var \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer */
		$pageRenderer = $GLOBALS['TSFE']->pageRenderer;
		$pageRenderer->addCssInlineBlock(
			$this->arguments['name'],
			$output,
			$this->arguments['compress'],
			$this->arguments['forceOnTop']);
	}

	/**
	 * @param string $path;
	 * @return string
	 */
	protected function resolvePath($path) {
		// /fileadmin/... wird zu fileadmin und kann damit ueber GeneralUtility::getFileAbsFileName aufgeloest werden
		if(strpos($path, '/') === 0) {
			$path = trim($path, '/');
		}

		return \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($path);
	}
}
