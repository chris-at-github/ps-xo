<?php

namespace Ps\Xo\Utilities;

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class GeneralUtility {

	/**
	 * @param mixed $iterable Object oder Array welches sortiert werden soll
	 * @param array $sorting Array mit Keys nachdem sortiert werden soll
	 * @param callable $callback Callback Funktion um aus $iterable den Key des Eintrags zu identifizieren
	 * @return array
	 */
	public static function sortIterableByField($iterable, $sorting, $callback) {
		$result = array_fill_keys($sorting, null);

		if(empty($iterable) === false) {
			foreach($iterable as $value) {
				$key = $callback($value);

				if(array_key_exists($key, $result) === true) {
					$result[$key] = $value;
				}
			}
		}

		return array_filter($result);
	}
}