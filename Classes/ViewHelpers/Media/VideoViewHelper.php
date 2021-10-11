<?php

namespace Ps\Xo\ViewHelpers\Media;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class VideoViewHelper extends AbstractViewHelper {

	/**
	 * Disable escaping of tag based ViewHelpers so that the rendered tag is not htmlspecialchar'd
	 *
	 * @var boolean
	 */
	protected $escapeOutput = false;

	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('options', 'array', 'autoplay, loop, mute', false);
		$this->registerArgument('width', 'string', '', false, 0);
		$this->registerArgument('height', 'string', '', false, 0);
		$this->registerArgument('file', 'object', '\TYPO3\CMS\Extbase\Domain\Model\FileReference|TYPO3\CMS\Core\Resource\FileReference', true);
	}

	/**
	 * Render a given media file
	 *
	 */
	public function render() {
		$file = $this->arguments['file'];
		$attributes = [];
		$options = $this->arguments['options'];

		// get Resource Object (non ExtBase version)
		if(is_callable([$file, 'getOriginalResource']) === true) {

			// We have a domain model, so we need to fetch the FAL resource object from there
			$file = $file->getOriginalResource();
		}

		// If autoplay isn't set manually check if $file is a FileReference take autoplay from there
		if(isset($options['autoplay']) === false && $file instanceof FileReference) {
			$autoplay = $file->getProperty('autoplay');

			if($autoplay !== null) {
				$options['autoplay'] = $autoplay;
			}
		}

//		if(isset($options['additionalAttributes']) && is_array($options['additionalAttributes'])) {
//			$attributes[] = GeneralUtility::implodeAttributes($options['additionalAttributes'], true, true);
//		}

//		if (isset($options['data']) && is_array($options['data'])) {
//			array_walk($options['data'], function (&$value, $key) {
//				$value = 'data-' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
//			});
//			$attributes[] = implode(' ', $options['data']);
//		}

		if((int) $this->arguments['width'] > 0) {
			$attributes[] = 'width="' . (int) $this->arguments['width'] . '"';
		}

		if((int) $this->arguments['height'] > 0) {
			$attributes[] = 'height="' . (int) $this->arguments['height'] . '"';
		}

		if(isset($options['controls']) === false || (int) $options['controls'] === 1) {
			$attributes[] = 'controls';
		}

		if(empty($options['autoplay']) === false) {
			$attributes[] = 'autoplay playsinline webkit-playsinline';
		}

		if(empty($options['muted']) === false || empty($options['autoplay']) === false) {
			$attributes[] = 'muted';
		}

		if(empty($options['loop']) === false) {
			$attributes[] = 'loop';
		}

//		if (isset($options['additionalConfig']) && is_array($options['additionalConfig'])) {
//			foreach ($options['additionalConfig'] as $key => $value) {
//				if((bool) $value) {
//					$attributes[] = htmlspecialchars($key);
//				}
//			}
//		}

		foreach(['class', 'dir', 'id', 'lang', 'style', 'title', 'accesskey', 'tabindex', 'onclick', 'controlsList', 'preload'] as $key) {
			if(empty($options[$key]) === false) {
				$attributes[] = $key . '="' . htmlspecialchars($options[$key]) . '"';
			}
		}

		// Clean up duplicate attributes
		$attributes = array_unique($attributes);

		return sprintf(
			'<video%s><source src="%s" type="%s"></video>',
			empty($attributes) ? '' : ' ' . implode(' ', $attributes),
			htmlspecialchars((string)$file->getPublicUrl(false)),
			$file->getMimeType()
		);
	}
}
