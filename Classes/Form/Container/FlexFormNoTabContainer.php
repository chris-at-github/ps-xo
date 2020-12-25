<?php
namespace Ps\Xo\Form\Container;

use TYPO3\CMS\Backend\Form\Container\AbstractContainer;

class FlexFormNoTabContainer extends AbstractContainer {

	/**
	 * @return array
	 */
	public function render() {
		$flexFormDataStructureIdentifier = $this->data['parameterArray']['fieldConf']['config']['dataStructureIdentifier'];
		$flexFormDataStructureArray = $this->data['parameterArray']['fieldConf']['config']['ds'];

		$options = $this->data;
		$options['flexFormDataStructureIdentifier'] = $flexFormDataStructureIdentifier;
		$options['flexFormDataStructureArray'] = $flexFormDataStructureArray;
		$options['flexFormRowData'] = $this->data['parameterArray']['itemFormElValue'];
		$options['renderType'] = 'flexFormNoTabsContainer';

		$result = $this->nodeFactory->create($options)->render();
		$result['requireJsModules'][] = 'TYPO3/CMS/Backend/FormEngineFlexForm';
		$result['html'] = '<div class="xo-formengine-hide-tabs">' . $result['html'] . '</div>';

		return $result;
	}
}