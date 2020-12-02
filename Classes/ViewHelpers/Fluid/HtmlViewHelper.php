<?php

namespace Ps\Xo\ViewHelpers\Fluid;

use TYPO3\CMS\Extbase\Annotation\Inject;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Christian Pschorr <pschorr.christian@gmail.com>
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
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class HtmlViewHelper extends AbstractViewHelper {

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 * @Inject
	 */
	protected $objectManager;

	/**
	 * @var bool
	 */
	protected $escapeOutput = false;

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('html', 'string', 'HTML String', true, null);
		$this->registerArgument('variables', 'array', '', false, []);
	}

	/**
	 * Wertet ein Template aus und parst es mit dem Fluid Standalone Parser
	 *
	 * @return string
	 */
	protected function render() {

		/* @var \TYPO3\CMS\Fluid\View\StandaloneView $view */
		$view = $this->objectManager->get(\TYPO3\CMS\Fluid\View\StandaloneView::class);
		$view->setTemplateSource($this->arguments['html']);
		$view->assignMultiple($this->arguments['variables']);

		return $view->render();
	}
}
