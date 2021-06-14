<?php

namespace Ps\Xo\Filter\DataProvider;

abstract class AbstractDataProvider {

	/**
	 * Instanz des Filter Service
	 *
	 * @var \Ps\Xo\Service\FilterService $filter
	 */
	protected $filter;

	/**
	 * @param array $data
	 * @param array $properties
	 * @return array $data
	 */
	abstract public function provide($data, $properties);

	/**
	 * @return \Ps\Xo\Service\FilterService
	 */
	public function getFilter() {
		return $this->filter;
	}

	/**
	 * @param \Ps\Xo\Service\FilterService $filter
	 */
	public function setFilter(\Ps\Xo\Service\FilterService $filter) {
		$this->filter = $filter;
	}
}