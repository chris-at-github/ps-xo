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

use Ps\Xo\Domain\Model\Page;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\VariableProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Laedt ein Page (Ps\Xo\Domain\Model) Model
 */
class ModelViewHelper extends DataViewHelper {

	/**
	 * @var boolean
	 */
	protected $escapeOutput = false;

	/**
	 * @var DataMapper
	 * @TYPO3\CMS\Extbase\Annotation\Inject
	 */
	protected static $dataMapper;

	/**
	 * Initialize all arguments with their description and options.
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('model', 'string', 'FQCN for page model', false, Page::class);
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
		$model = null;

		$dataMapper = GeneralUtility::makeInstance(ObjectManager::class)->get(DataMapper::class);
		$objects = $dataMapper->map($arguments['model'], [$data]);

		if($objects[0] instanceof $arguments['model']) {
			$model = $objects[0];
		}

		$templateContainer->add($arguments['as'], $model);
		$output = $renderChildrenClosure();
		$templateContainer->remove($arguments['as']);

		return $output;
	}
}