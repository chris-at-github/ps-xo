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

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Extbase\Annotation\Inject;

class PictureViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper {

	/**
	 * @var \TYPO3\CMS\Extbase\Service\ImageService
	 * @Inject
	 */
	protected $imageService;

	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('sizes', 'array', 'Specifies the sizes ({size: {width:200, height:200}, media: "mediaQuery"}) for the image', false);
	}

	/**
	 * Render a given media file
	 *
	 */
	public function render() {
		$file = $this->arguments['src'];

		if($file instanceof \TYPO3\CMS\Extbase\Domain\Model\FileReference && $this->arguments['treatIdAsReference'] === true) {
			$this->arguments['src'] = $file->getUid();
		}

		// get Resource Object (non ExtBase version)
		if(is_callable([$file, 'getOriginalResource'])) {

			// We have a domain model, so we need to fetch the FAL resource object from there
			$file = $file->getOriginalResource();
		}

		if(isset($this->arguments['fileExtension']) === false) {
			$this->arguments['fileExtension'] = null;
		}

		return $this->renderImage($file, $this->arguments['width'], $this->arguments['height'], $this->arguments['fileExtension']);
	}

	/**
	 * Render img tag
	 *
	 * @param FileInterface $image
	 * @param string $width
	 * @param string $height
	 * @param string|null $fileExtension
	 * @return string Rendered img tag
	 */
	protected function renderImage(FileInterface $image, $width, $height, ?string $fileExtension) {

		// picture tags nur auf "normalen" Seiten (nicht amp...), wenn mehrere Größen übergeben
		if(isset($this->arguments['sizes']) && count($this->arguments['sizes']) !== 0) {
			$sizes = $this->arguments['sizes'];

//			// Entferne Attribut, da es sonst im Quellcode ausgegeben wird
//			$this->tag->removeAttribute('sizes');
			return $this->renderPicture($image, $sizes, parent::render());
		} else {
			//return parent::renderImage($image, $width, $height, $fileExtension);
		}
	}

	/**
	 * @param FileInterface|FileReference $image
	 * @param array $sizes
	 * @param string $default
	 * @return string
	 */
	protected function renderPicture($image, $sizes, $default) {
		$content = '';

		foreach($sizes as $size) {
			$width = null;
			if(empty($size['width']) === false) {
				$width = $size['width'];
			}

			$height = null;
			if(empty($size['height']) === false) {
				$height = $size['height'];
			}

			$processingInstructions = [
				'width' => $width,
				'height' => $height,
				'crop' => null
			];

			if(empty($size['maxWidth']) === false) {
				$processingInstructions['maxWidth'] = $size['maxWidth'];
			}

			if(empty($size['maxHeight']) === false) {
				$processingInstructions['maxHeight'] = $size['maxHeight'];
			}

			if(empty($size['cropVariant']) === false) {

				$cropString = '';
				if($image instanceof FileReference) {
					$cropString = $image->getProperty('crop');
				}

				$cropVariantCollection = CropVariantCollection::create((string)$cropString);
				$cropArea = $cropVariantCollection->getCropArea($size['cropVariant']);

				if($cropArea->isEmpty() === false) {
					$processingInstructions['crop'] = $cropArea->makeAbsoluteBasedOnFile($image);
				}
			}

			$imageService = $this->imageService;
			$processedImage = $imageService->applyProcessingInstructions($image, $processingInstructions);
			$imageUri = $imageService->getImageUri($processedImage);
			$source = new TagBuilder('source');

			$source->addAttribute('srcset', $imageUri);
			$source->addAttribute('media', $size['mediaQuery']);

			$source->setTagName('source');

			$content .= $source->render();
		}

		$content .= $default;
		$picture = new TagBuilder('picture');
		$picture->setContent($content);

		return $picture->render();
	}
}
