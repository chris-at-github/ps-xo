<?php

namespace Ps\Xo\ViewHelpers\Language;

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

use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Ueberprueft ob die uebergebene FileReference ein Bild ist
 */
class GetViewHelper extends AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeOutput = false;

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		parent::initializeArguments();

		$this->registerArgument('languageId', 'int', 'Language Id', true);
		$this->registerArgument('as', 'string', 'Language variable name', false, 'language');
	}

//	/**
//	 * @param array $arguments
//	 * @param \Closure $renderChildrenClosure
//	 * @param RenderingContextInterface $renderingContext
//	 * @return SiteLanguage
//	 */
//	public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {
//
//		DebuggerUtility::var_dump($GLOBALS['TSFE']);
//
//		return $GLOBALS['TSFE'];
//	}


	/**
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 * @return mixed
	 */
	public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {

		/** @var VariableProviderInterface  $templateContainer */
		$templateContainer = $renderingContext->getVariableProvider();
		$language = static::getLanguage((int) $arguments['languageId']);
		$as = $arguments['as'];

		$templateContainer->add($as, $language);
		$output = $renderChildrenClosure();
		$templateContainer->remove($as);

		return $output;
	}

	/**
	 * @param int $languageId
	 * @return SiteLanguage|null
	 */
	protected static function getLanguage(int $languageId) {
		$languages = $GLOBALS['TSFE']->getSite()->getLanguages();

		/** @var SiteLanguage $language */
		foreach($languages as $language) {
			if($languageId === $language->getLanguageId()) {
				return $language;
			}
		}

		return null;
	}
}
