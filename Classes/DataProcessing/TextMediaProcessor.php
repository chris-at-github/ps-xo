<?php

/*
 * This file is part of the package bk2k/bootstrap-package.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Ps\Xo\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

class TextMediaProcessor extends ModuleProcessor implements DataProcessorInterface {

	/**
	 * @var string[]
	 */
	protected $importCssFiles = [
		'/assets/css/modules/text-media.css'
	];

	/**
	 * @param ContentObjectRenderer $cObj The data of the content element or page
	 * @param array $contentObjectConfiguration The configuration of Content Object
	 * @param array $processorConfiguration The configuration of this processor
	 * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
	 * @return array the processed data as key/value store
	 */
	public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData) {

		if(isset($processedData['gallery']) === true) {
			if($processedData['gallery']['position']['vertical'] === 'intext') {
				$mediaQuery = $processedData['settings']['modules']['textmedia']['pictureMediaQueries']['intext'];
			} else {
				$mediaQuery = $processedData['settings']['modules']['textmedia']['pictureMediaQueries']['maximal'];
			}

			foreach($processedData['gallery']['rows'] as &$row) {
				foreach($row['columns'] as &$column) {
					$column['dimensions']['mediaQuery'] = [];

					foreach($mediaQuery as $breakpoint => $options) {

						// (maximale) Breite nicht groeßer als der angegebene Wert
						if(isset($options['width']) === true && $options['width'] > $column['dimensions']['width']) {
							$options['width'] = (string) $column['dimensions']['width'];
						}

						if(isset($options['maxWidth']) === true && $options['maxWidth'] > $column['dimensions']['width']) {
							$options['maxWidth'] = (string) $column['dimensions']['width'];
						}

						// Media-Query String zusammenbauen
						$options['mediaQuery'] = '(min-width: ' . $breakpoint . 'px)';

						// fertiges Array fuers Template
						$column['dimensions']['mediaQuery'][] = $options;
					}
				}
			}
		}

		return $processedData;
	}
}