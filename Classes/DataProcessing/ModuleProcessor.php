<?php

/*
 * This file is part of the package bk2k/bootstrap-package.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Ps\Xo\DataProcessing;

use TYPO3\CMS\Core\Page\PageRenderer;

class ModuleProcessor {

	/**
	 * @var string[]
	 */
	protected $importCssFiles = [];

	/**
	 * @return void
	 */
	public function __construct() {
		$this->addImportCssFiles();
	}

	/**
	 * Fuegt die Css Dateien im Head hinzu
	 *
	 * @return void
	 */
	protected function addImportCssFiles() {

		/** @var PageRenderer $pageRenderer */
		$pageRenderer = $GLOBALS['TSFE']->pageRenderer;

		foreach($this->importCssFiles as $importCssFile) {
			$pageRenderer->addCssFile($importCssFile);
		}
	}
}