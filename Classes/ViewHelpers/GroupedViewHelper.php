<?php

namespace Ps\Xo\ViewHelpers;

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\Variables\VariableExtractor;
use TYPO3Fluid\Fluid\Core\ViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class GroupedViewHelper extends AbstractViewHelper {
	use CompileWithRenderStatic;

	/**
	 * @var boolean
	 */
	protected $escapeOutput = false;

	/**
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('each', 'mixed', 'The array or \SplObjectStorage to iterated over', true);
		$this->registerArgument('as', 'string', 'The name of the iteration variable', true);
		$this->registerArgument('groupBy', 'string', 'Group by this property', true);
		$this->registerArgument('groupKey', 'string', 'The name of the variable to store the current group', false, 'groupKey');
	}

	/**
	 * @param array $arguments
	 * @param \Closure $renderChildrenClosure
	 * @param RenderingContextInterface $renderingContext
	 * @return mixed
	 */
	public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {
		$each = $arguments['each'];
		$as = $arguments['as'];
		$groupBy = $arguments['groupBy'];
		$groupKey = $arguments['groupKey'];
		$output = '';

		if($each === null) {
			return '';
		}

		if(is_object($each)) {
			if(!$each instanceof \Traversable) {
				throw new ViewHelper\Exception('GroupedViewHelper only supports arrays and objects implementing \Traversable interface', 1253108907);
			}
			$each = iterator_to_array($each);
		}

		$groups = static::groupElements($each, $groupBy);

		$templateVariableContainer = $renderingContext->getVariableProvider();
		foreach($groups['values'] as $currentGroupIndex => $group) {
			$templateVariableContainer->add($groupKey, $groups['keys'][$currentGroupIndex]);
			$templateVariableContainer->add($as, $group);
			$output .= $renderChildrenClosure();
			$templateVariableContainer->remove($groupKey);
			$templateVariableContainer->remove($as);
		}

		return $output;
	}


	/**
	 * Groups the given array by the specified groupBy property.
	 *
	 * @param array|object $elements The array / traversable object to be grouped
	 * @param string $groupBy Group by this property
	 * @return array The grouped array in the form array('keys' => array('key1' => [key1value], 'key2' => [key2value], ...), 'values' => array('key1' => array([key1value] => [element1]), ...), ...)
	 * @throws ViewHelper\Exception
	 */
	protected static function groupElements($elements, $groupBy) {
		$extractor = new VariableExtractor();
		$groups = [
			'keys' => [],
			'values' => []
		];

		foreach($elements as $key => $value) {
			if(is_array($value)) {
				$currentGroupIndex = isset($value[$groupBy]) ? $value[$groupBy] : null;

			} elseif(is_object($value)) {
				$currentGroupIndex = $extractor->getByPath($value, $groupBy);

			} else {
				throw new ViewHelper\Exception('GroupedForViewHelper only supports multi-dimensional arrays and objects', 1253120365);
			}

			$currentGroupKeyValue = $currentGroupIndex;
			if($currentGroupIndex instanceof \TYPO3\CMS\Extbase\DomainObject\AbstractEntity) {
				$currentGroupIndex = $currentGroupIndex->getUid();

			} elseif($currentGroupIndex instanceof \DateTime) {
				$currentGroupIndex = $currentGroupIndex->format(\DateTime::RFC850);

			} elseif(is_object($currentGroupIndex)) {
				$currentGroupIndex = spl_object_hash($currentGroupIndex);
			}

			$groups['keys'][$currentGroupIndex] = $currentGroupKeyValue;
			$groups['values'][$currentGroupIndex][$key] = $value;
		}

		return $groups;
	}
}
