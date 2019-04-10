<?php

// ---------------------------------------------------------------------------------------------------------------------
// Weitere Felder in TT-Content
$tmpXoTtAddressColumns = [
	'tx_xo_content' => [
		'exclude' => true,
		'label' => 'CONTENT',
		'config' => [
			'type' => 'input',
			'size' => 4,
			'eval' => 'int'
		]
	]
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_address', $tmpXoTtAddressColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_address', 'paletteHidden', 'tx_xo_content', 'after:hidden');