<?php

namespace Ps\Xo\Filter\DataProvider;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class CategoryTreeDataProvider extends AbstractDataProvider {

	/**
	 * @var LanguageAspect
	 */
	protected $languageAspect = null;

	/**
	 * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
	 */
	protected function getFrontend() {
		return $GLOBALS['TSFE'];
	}

	/**
	 * @return LanguageAspect
	 */
	protected function getLanguageAspect() {
		if($this->languageAspect === null) {
			$this->languageAspect = GeneralUtility::makeInstance(Context::class)->getAspect('language');
		}

		return $this->languageAspect;
	}

	/**
	 * @param array $data
	 * @param array $properties
	 * @return array $data
	 */
	public function provide($data, $properties): array {
		$data['data'] = $this->getChildren($properties['parent'], $data, $properties);
		return $data;
	}

	/**
	 * @param int $parent
	 * @param array $data
	 * @param array $properties
	 * @return array
	 */
	protected function getChildren($parent, $data, $properties) {

		$categories = [];

		/** @var \TYPO3\CMS\Core\Database\Query\QueryBuilder  $queryBuilder */
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_category')->createQueryBuilder();
		$query = $queryBuilder->select('*')
			->from('sys_category')
			->where(
				$queryBuilder->expr()->in('sys_language_uid', [0, -1]),
				$queryBuilder->expr()->eq('parent', $queryBuilder->createNamedParameter($parent, \PDO::PARAM_INT))
			);

		if(isset($properties['blacklist']) === true && empty($properties['blacklist']) === false) {
			$queryBuilder->andWhere(
				$queryBuilder->expr()->notIn('uid', $properties['blacklist'])
			);
		}

		if(isset($properties['whitelist']) === true && empty($properties['whitelist']) === false) {
			$queryBuilder->andWhere(
				$queryBuilder->expr()->in('uid', $properties['whitelist'])
			);
		}

		$queryBuilder->orderBy('sorting', 'asc');

		$statement = $query->execute();

		// Zum Verarbeiten muss der Selected Wert immer ein Array sein (kann durch Default ein String sein)
		if(gettype($data['selected']) !== 'array') {
			$data['selected'] = [$data['selected']];
		}

		if(empty($properties['sorting']) === true) {
			$properties['sorting'] = 'sorting';
		}

		while($row = $statement->fetch()) {

			// Uebersetzung laden
			if(empty($row) === false && (int) $row['sys_language_uid'] !== $this->getLanguageAspect()->getContentId()) {
				$row = $this->getFrontend()->sys_page->getRecordOverlay(
					'sys_category',
					$row,
					$this->getLanguageAspect()->getContentId()
				);
			}

			// Identifier erstellen
			$identifier = 'fi' . md5($data['identifier'] . $row['uid']);
			$groupIdentifier = 'fi' . md5($data['identifier'] . $parent);

			// Sortierungsfeld / -wert
			$sorting = $row[$properties['sorting']];

			if(is_numeric($sorting) === false) {
				$sorting = str_replace(['ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü'], ['ae', 'oe', 'ue', 'ae', 'oe', 'ue'], strtolower($sorting));
			}

			// Eintraege vorselektieren
			$selected = false;
			if(is_array($data['selected'][$groupIdentifier]) === true && in_array($row['uid'], $data['selected'][$groupIdentifier]) === true) {
				$selected = true;
			}

			// moegliche Kindelemente abfragen
			$children = $this->getChildren((int) $row['uid'], $data, $properties);
			if(empty($children) === true) {
				$children = null;
			}

			// Datenuebernahme
			$categories[] = [
				'value' => $row['uid'],
				'label' => $row['title'],
				'selected' => $selected,
				'identifier' => $identifier,
				'sorting' => $sorting,
				'children' => $children
			];
		}

		// Nachtraegliche Sortierung ueber PHP -> Sortierung nach Titel wg. Overlay nicht ueber SQL moeglich
		usort($categories, function($a, $b) {

			if(is_numeric($a['sorting']) === true && is_numeric($b['sorting']) === true) {
				return $a['sorting'] < $b['sorting'] ? -1 : ($a['sorting'] > $b['sorting'] ? 1 : 0);

			} else {
				return strcasecmp($a['sorting'], $b['sorting']);
			}
		});

		return $categories;
	}
}