<?php

namespace Ps\Xo\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

/**
 * Trims $content by stripping off $characters (string list
 * of individual chars to strip off, default is all whitespaces).
 */
class ToLowerViewHelper extends AbstractViewHelper {

	use CompileWithContentArgumentAndRenderStatic;

	/**
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('value', 'string', 'String to lower');
	}

	/**
	 * Trims content by stripping off $characters
	 *
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 * @return mixed
	 */
	public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {
		return strtolower($renderChildrenClosure());
	}
}