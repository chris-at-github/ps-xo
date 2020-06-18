<?php
namespace Ps\Xo\Condition;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Ueberpruft ob bei einem neuen (GET:defVals) oder bestehenden TtContent-Element (Abfrage ueber UID) ob das abgefragte
 * Feld dem uebergebenen Werte(n) entspricht
 *
 * @see https://docs.typo3.org/typo3cms/TyposcriptReference/Conditions/Reference/Index.html#id53
 */
class ContentFieldCondition extends \TYPO3\CMS\Core\Configuration\TypoScript\ConditionMatching\AbstractCondition {

	protected $comparators = "/(!=|==)/";

	/**
	 * Evaluate condition
	 *
	 * @param array $expressions
	 * @return bool
	 */
	public function matchCondition(array $expressions) {
		$parameter = $_REQUEST;
		$parent = null;
		$page = null;
		$irre = null;
		$matches = [];

		// Neuer Inhalt (defVals) und Auswahl defVals -> tt_content vorhanden
		if(isset($parameter['defVals']) === true && isset($parameter['defVals']['tt_content']) === true && isset($parameter['defVals']['tt_content']) === true) {
			$fields = $parameter['defVals']['tt_content'];

			// PID auslesen -> versteckt sind in edit -> tt_content -> PID => new
			if(isset($parameter['edit']) === true && isset($parameter['edit']['tt_content']) === true) {
				$fields['pid'] = (int) trim(key($parameter['edit']['tt_content']));
			}

		// Bestehender Inhalt -> Laden anhand der UID (edit -> tt_content)
		} elseif(isset($parameter['edit']) === true && isset($parameter['edit']['tt_content']) === true) {
			$uid = key($parameter['edit']['tt_content']);

			if((int) $uid !== 0) {
				$fields = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'tt_content', 'uid = ' . (int) $uid);

				if(empty($fields) === false && isset($fields['pid']) === true) {
					$fields['pid'] = (int) $fields['pid'];
				}
			}

		} elseif(isset($parameter['ajax']) === true && isset($parameter['ajax'][0])) {
			$uid = $matches[1];

			// IRRE
			// Bestehender Datensatz
			if(preg_match('/(.*)-(.*)-(\d+)-(.*)-(.*)-(\d+)$/', $parameter['ajax'][0], $match)) {
				$irre = [
					'parent' => $match[2],
					'pid' => (int) $match[3],
					'field' => $match[4],
					'table' => $match[5],
					'uid' => $match[6]
				];

			// Neuer Datensatz
			} elseif(preg_match('/(.*)-(.*)-(\d+)-(.*)-(.*)$/', $parameter['ajax'][0], $match)) {
				$irre = [
					'parent' => $match[2],
					'pid' => (int) $match[3],
					'field' => $match[4],
					'table' => $match[5]
				];
			}

			// Zwei verschiedene IRRE Ausfuehrungen -> irgendwie kommt man an die UID
			if(preg_match('/tt_content-(\d+)$/', $parameter['ajax'][0], $match)) {
				$uid = $match[1];

			} elseif(preg_match('/(.*)-tt_content-(\d+)-(.*)$/', $parameter['ajax'][0], $match)) {
				$uid = $match[2];
			}

			return false;

			if((int) $uid !== 0) {
				$fields = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'tt_content', 'uid = ' . (int) $uid);

				if(empty($fields) === false && isset($fields['pid']) === true) {
					$fields['pid'] = (int) $fields['pid'];
				}
			}
		}

		if(empty($fields) === true && empty($irre) === true) {
			return false;
		}

		if(isset($fields['pid']) === true) {
			$page = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'pages', 'uid = ' . (int) $fields['pid']);
		}

		// IF $fields => tx_flux_parent = ID FCE Elternelement
		// @see: http://redmine/issues/17333
		if((int) $fields['tx_flux_parent'] !== 0) {
			$parent = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('*', 'tt_content', 'uid = ' . (int) $fields['tx_flux_parent']);
		}

		foreach($expressions as $expression) {
			$comparator = [];

			if(preg_match($this->comparators, $expression, $comparator) !== false) {
				$comparator = $comparator[0];

				list($name, $value) = preg_split($this->comparators, $expression);

				// Wenn Keyword parent:* im $name vorkommt wende den Vergleich auf das Eltern-Element an
				if(strpos($name, 'parent:') !== false && $parent !== null) {
					$name = str_replace('parent:', null, $name);

					if($this->checkCondition($parent, $name, $value, $comparator) === false) {
						return false;
					}

				// Wenn Keyword page:* im $name vorkommt wende den Vergleich auf die Seiteneigenschaften an
				} elseif(strpos($name, 'page:') !== false && $page !== null) {
					$name = str_replace('page:', null, $name);

					if($this->checkCondition($page, $name, $value, $comparator) === false) {
						return false;
					}

				// Wenn Keyword irre:* im $name vorkommt wende den Vergleich auf die uebergeben IRRE-Eigenschaften
				} elseif(strpos($name, 'irre:') !== false && $irre !== null) {
					$name = str_replace('irre:', null, $name);

					if($this->checkCondition($irre, $name, $value, $comparator) === false) {
						return false;
					}

				} elseif($this->checkCondition($fields, $name, $value, $comparator) === false) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * @param array $fields
	 * @param string $name
	 * @param string $value
	 * @param string $comparator
	 * @return boolean
	 */
	protected function checkCondition($fields, $name, $value, $comparator) {
		$result = true;

		// Feld nicht vorhanden oder entspricht nicht dem Wert
		if(isset($fields[$name]) === false || $fields[$name] != $value) {
			$result = false;
		}

		// Wenn ungleich Result einfach umdrehen
		if($comparator === '!=') {
			$result = !$result;
		}

		return $result;
	}
}