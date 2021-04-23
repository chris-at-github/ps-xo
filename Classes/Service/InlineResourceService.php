<?php

namespace Ps\Xo\Service;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Json Service Class
 */
class InlineResourceService {

	/**
	 * @param string $path;
	 * @param array $options
	 * @return string
	 */
	protected function resolveAbsolutePath(string $path, $options = []): string {

		// /fileadmin/... wird zu fileadmin und kann damit ueber GeneralUtility::getFileAbsFileName aufgeloest werden
		if(strpos($path, '/') === 0) {
			$path = trim($path, '/');
		}

		$path = GeneralUtility::getFileAbsFileName($path);
		$applicationContext = Environment::getContext();
		$fileExtension = pathinfo($path, PATHINFO_EXTENSION);

		DebuggerUtility::var_dump($fileExtension);

		if($options['useMinifyOnProduction'] === true && $applicationContext->isDevelopment() === false) {
			$minifyPath = preg_replace('/\.' . $fileExtension . '$/', '.min.' . $fileExtension, $path);

			if(is_file($minifyPath) === true) {
				$path = $minifyPath;
			}
		}

		DebuggerUtility::var_dump($path);

		return $path;
	}

	/**
	 * @return PageRenderer
	 */
	protected function getPageRenderer() {
		return $GLOBALS['TSFE']->pageRenderer;
	}

	/**
	 * @param string $file
	 * @param array $options
	 */
	public function addCssResource(string $file, $options = []) {
		$css = file_get_contents($this->resolveAbsolutePath(trim($file), $options));
		$pageRenderer = $this->getPageRenderer();
		$name = md5($file);

		$pageRenderer->addCssInlineBlock($name, $css);
	}
}