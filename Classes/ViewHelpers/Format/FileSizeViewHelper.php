<?php

namespace Ps\Xo\ViewHelpers\Format;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

class FileSizeViewHelper extends AbstractViewHelper {

	use CompileWithContentArgumentAndRenderStatic;

	/**
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('value', 'string', 'filesize in bytes');
		$this->registerArgument('labels', 'string', 'GeneralUtility::formatSize -> $labels', false, ' b| kb| mb| gb');
	}

	/**
	 * Returns a formated file size
	 *
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 * @return mixed
	 */
	public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {
		return GeneralUtility::formatSize($renderChildrenClosure(), $arguments['labels']);
	}
}