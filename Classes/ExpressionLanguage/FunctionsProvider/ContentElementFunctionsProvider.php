<?php

declare(strict_types=1);

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

namespace Ps\Xo\ExpressionLanguage\FunctionsProvider;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * Class TypoScriptConditionProvider
 * @internal
 */
class ContentElementFunctionsProvider implements ExpressionFunctionProviderInterface {

	/**
	 * @return ExpressionFunction[] An array of Function instances
	 */
	public function getFunctions() {
		return [
			$this->getGetFieldFunction(),
		];
	}

	/**
	 * @return ExpressionFunction
	 */
	protected function getGetFieldFunction(): ExpressionFunction {
		return new ExpressionFunction('field', function() {
			// Not implemented, we only use the evaluator
		}, function($arguments, $field) {
			$data = $this->getFieldData($arguments);

			if(isset($data[$field]) === true) {
				return $data[$field];
			}

//			DebuggerUtility::var_dump($fields);
//			DebuggerUtility::var_dump($arguments);
//			DebuggerUtility::var_dump($str);

			return null;
		});
	}

	/**
	 * @param array $arguments
	 * @return array
	 */
	protected function getFieldData($arguments) {

		/** @var \TYPO3\CMS\Core\ExpressionLanguage\RequestWrapper $request */
		$request = $arguments['request'];
		$fields = [];
		$parameter = [];

//		DebuggerUtility::var_dump($arguments);

		// Neuer Inhalt (defVals) und Auswahl defVals -> tt_content vorhanden
		if(isset($request->getQueryParams()['defVals']) === true && isset($request->getQueryParams()['defVals']['tt_content']) === true) {
			$fields = $request->getQueryParams()['defVals']['tt_content'];

			// Bestehender Inhalt -> Laden anhand der UID (edit -> tt_content)
		} elseif(isset($request->getQueryParams()['edit']) === true && isset($request->getQueryParams()['edit']['tt_content']) === true) {
			$uid = key($request->getQueryParams()['edit']['tt_content']);

			if((int) $uid !== 0) {
				$fields = $this->getRecord($uid);
			}

//		} elseif(isset($parameter['ajax']) === true && isset($parameter['ajax'][0])) {
//			$uid = $matches[1];
//
//			// IRRE
//			// Bestehender Datensatz
//			if(preg_match('/(.*)-(.*)-(\d+)-(.*)-(.*)-(\d+)$/', $parameter['ajax'][0], $match)) {
//				$irre = [
//					'parent' => $match[2],
//					'pid' => (int) $match[3],
//					'field' => $match[4],
//					'table' => $match[5],
//					'uid' => $match[6]
//				];
//
//				// Neuer Datensatz
//			} elseif(preg_match('/(.*)-(.*)-(\d+)-(.*)-(.*)$/', $parameter['ajax'][0], $match)) {
//				$irre = [
//					'parent' => $match[2],
//					'pid' => (int) $match[3],
//					'field' => $match[4],
//					'table' => $match[5]
//				];
//			}
//
//			// Zwei verschiedene IRRE Ausfuehrungen -> irgendwie kommt man an die UID
//			if(preg_match('/tt_content-(\d+)$/', $parameter['ajax'][0], $match)) {
//				$uid = $match[1];
//
//			} elseif(preg_match('/(.*)-tt_content-(\d+)-(.*)$/', $parameter['ajax'][0], $match)) {
//				$uid = $match[2];
//			}
//
//			if((int) $uid !== 0) {
//				$fields = $this->getRecord($uid);
//
//				if(empty($fields) === false && isset($fields['pid']) === true) {
//					$fields['pid'] = (int) $fields['pid'];
//				}
//			}
		}

		return $fields;
	}

	/**
	 * @param int $uid
	 * @param string $table
	 * @return array
	 */
	protected function getRecord($uid, $table = 'tt_content') {

		/** @var QueryBuilder $queryBuilder */
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);

		$statement = $queryBuilder->select('*')->from($table)->where(
			$queryBuilder->expr()->eq('uid', (int) $uid)
		)->execute();

		return $statement->fetch();
	}
}