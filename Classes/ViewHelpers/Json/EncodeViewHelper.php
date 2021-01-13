<?php

namespace Ps\Xo\ViewHelpers\Json;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Extbase\Annotation\Inject;

/**
 * Bindet Inline JS im Head ueber eine Datei ein
 */
class EncodeViewHelper extends AbstractViewHelper {

	/**
	 * @var bool
	 */
	protected $escapeOutput = false;

	/**
	 * @var \Ps\Xo\Service\JsonService
	 * @Inject
	 */
	protected $jsonService;

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		parent::initializeArguments();

		$this->registerArgument('value', 'mixed', 'Value for to json', true);
		$this->registerArgument('options', 'array', 'Settings for to json service', false, []);
	}

	/**
	 * @return string
	 */
	public function render() {
		return $this->jsonService->toJson($this->arguments['value'], $this->arguments['options']);
	}
}