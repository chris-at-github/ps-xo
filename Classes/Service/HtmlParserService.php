<?php

namespace Ps\Xo\Service;

use TYPO3\CMS\Core\Html\HtmlParser;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * HtmlParser Service Class
 */
class HtmlParserService {

	/**
	 * Ersetzt innerhalb eines UL|OL HTML Elements eine verschachtelte Klasse (options: class)
	 *
	 * @param string $value
	 * @param array $options
	 */
	public function fixListClassAttribute($value, $options = []) {
		if(preg_match("/<" . $options['tag'] . "[^>]*>\K.*(?=<\/" . $options['tag'] . ">)/Uis", $value, $match) !== 0) {
			$value = str_replace(
				$match[0],
				str_replace('<' . $options['tag'] . ' class="' . $options['class'] . '">', '<' . $options['tag'] . '>', $match[0]),
				$value
			);
		}

		return $value;
	}
}