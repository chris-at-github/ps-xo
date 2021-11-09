<?php

namespace Ps\Xo\ViewHelpers\Page;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020 Christian Pschorr <pschorr.christian@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Context\Context;

/**
 * Laedt ein Page (Ps\Xo\Domain\Model) Model
 */
class DataViewHelper extends AbstractViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeOutput = false;

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		parent::initializeArguments();

		$this->registerArgument('uid', 'int', 'Page Uid', true);
		$this->registerArgument('as', 'string', 'Page variable name', false, 'page');
	}

	/**
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 * @return mixed
	 */
	public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {

		/** @var VariableProviderInterface  $templateContainer */
		$templateContainer = $renderingContext->getVariableProvider();
		$data = static::getData((int) $arguments['uid']);
		$as = $arguments['as'];

		$templateContainer->add($as, $data);
		$output = $renderChildrenClosure();
		$templateContainer->remove($as);

		return $output;
	}

	/**
	 * @param int $uid
	 * @return array|null
	 */
	protected static function getData(int $uid) {

		/** @var Context $context */
		$context = GeneralUtility::makeInstance(Context::class);
		$languageId = $context->getPropertyFromAspect('language', 'id');

		/** @var QueryBuilder $queryBuilder */
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages')->createQueryBuilder();
		$queryBuilder
			->select('*')
			->from('pages');

		if($languageId === 0) {
			$queryBuilder->where(
				$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid,\PDO::PARAM_INT))
			);

		} else {
			$queryBuilder->where(
				$queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($languageId,\PDO::PARAM_INT)),
				$queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter($uid,\PDO::PARAM_INT))
			);
		}

		if(empty($data = $queryBuilder->execute()->fetch()) === false) {
			return $data;
		}

		return null;
	}
}