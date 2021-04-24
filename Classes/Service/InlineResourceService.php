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

		if($options['useMinifyOnProduction'] === true && $applicationContext->isDevelopment() === false) {
			$minifyPath = preg_replace('/\.' . $fileExtension . '$/', '.min.' . $fileExtension, $path);

			if(is_file($minifyPath) === true) {
				$path = $minifyPath;
			}
		}

		return $path;
	}

	/**
	 * @return PageRenderer
	 */
	protected function getPageRenderer(): PageRenderer {
		return $GLOBALS['TSFE']->pageRenderer;
	}

	/**
	 * @param string $file
	 * @return string
	 */
	protected function getName(string $file): string {
		return md5($file);
	}

	/**
	 * @param string $file
	 * @param array $options
	 * @return string
	 */
	protected function getSource(string $file, $options = []): string {
		return file_get_contents($this->resolveAbsolutePath(trim($file), $options));
	}

	/**
	 * @param array $options
	 * @return array
	 */
	protected function getOptions($options = []): array {
		if(isset($options['compress']) === false) {
			$options['compress'] = false;
		}

		if(isset($options['forceOnTop']) === false) {
			$options['forceOnTop'] = false;
		}

		return $options;
	}

	/**
	 * @param string $file
	 * @param array $options
	 */
	public function addCss(string $file, $options = []) {
		$options = $this->getOptions($options);

		$this->getPageRenderer()->addCssInlineBlock(
			$this->getName($file),
			$this->getSource($file, $options),
			$options['compress'],
			$options['forceOnTop']
		);
	}

	/**
	 * @param string $file
	 * @param array $options
	 */
	public function addJs(string $file, $options = []) {
		$options = $this->getOptions($options);

		$this->getPageRenderer()->addJsInlineCode(
			$this->getName($file),
			$this->getSource($file, $options),
			$options['compress'],
			$options['forceOnTop']
		);
	}
}