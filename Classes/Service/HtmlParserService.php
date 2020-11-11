<?php

namespace Ps\Xo\Service;

use TYPO3\CMS\Core\Html\HtmlParser;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * HtmlParser Service Class
 */
class HtmlParserService {

	/**
	 * Ersetzt innerhalb eines UL|OL HTML Elements eine doppelt gesetzte Klasse auf (eventuellen) Unterelementen
	 *
	 * @param string $value
	 * @param array $options
	 */
	public function fixListClassAttribute($value, $options = []) {
		if(preg_match("/(<" . $options['tag'] . "[^>]*>)\K.*(?=<\/" . $options['tag'] . ">)/Uis", $value, $match) !== 0) {
			if(preg_match("/\s?class=\"(.*)\"\s?/Uis", $match[1], $classValue) !== 0) {
				$value = str_replace(
					$match[0],
					str_replace('<' . $options['tag'] . ' class="' . $classValue[1] . '">', '<' . $options['tag'] . '>', $match[0]),
					$value
				);
			}
		}

		return $value;
	}
}