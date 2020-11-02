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
use TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Extbase\Annotation\Inject;

class PictureViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\MediaViewHelper {

	/**
	 * @var \TYPO3\CMS\Extbase\Service\ImageService
	 * @Inject
	 */
	protected $imageService;

	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('sizes', 'array', 'Specifies the sizes ({size:{width:200,height:200},media:"mediaQuery"}) for the image', false);
	}

	/**
	 * Render a given media file
	 *
	 */
	public function render() {
		$file = $this->arguments['file'];

		// get Resource Object (non ExtBase version)
		if(is_callable([$file, 'getOriginalResource'])) {
			// We have a domain model, so we need to fetch the FAL resource object from there
			$file = $file->getOriginalResource();
		}

//		// get file, width and height

		$width = $this->arguments['width'];
		$height = $this->arguments['height'];

		return $this->renderImage($file, $width, $height);
	}

	/**
	 * Render img tag
	 *
	 * @param FileInterface $image
	 * @param string $width
	 * @param string $height
	 * @return string Rendered img tag
	 */
	protected function renderImage(FileInterface $image, $width, $height) {

		// picture tags nur auf "normalen" Seiten (nicht amp...), wenn mehrere Größen übergeben
		if(isset($this->arguments['sizes']) && count($this->arguments['sizes']) !== 0) {
			$sizes = $this->arguments['sizes'];

//			// Entferne Attribut, da es sonst im Quellcode ausgegeben wird
//			$this->tag->removeAttribute('sizes');
			return $this->renderPicture($image, $sizes, parent::renderImage($image, $width, $height));
		} else {
			return parent::renderImage($image, $width, $height);
		}
	}


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

			if(empty($size['cropVariant']) === false) {

				$cropString = '';
				if($image instanceof FileReference) {
					$cropString = $image->getProperty('crop');
				}

				$cropVariantCollection = CropVariantCollection::create((string) $cropString);
				$cropArea = $cropVariantCollection->getCropArea($size['cropVariant']);

				if($cropArea->isEmpty() === false) {
					$processingInstructions['crop'] =  $cropArea->makeAbsoluteBasedOnFile($image);
				}
			}

			$imageService = $this->getImageService();
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
