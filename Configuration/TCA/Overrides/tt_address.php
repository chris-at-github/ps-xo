<?php

// ---------------------------------------------------------------------------------------------------------------------
// Weitere Felder in TT-Content
$tmpXoTtAddressColumns = [
	'record_type' => [
		'exclude' => 0,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.record_type',
		'config' => [
			'type' => 'select',
			'renderType' => 'selectSingle',
			'items' => [
				['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0]
			]
		]
	],
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
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_address', 'name', 'record_type', 'before:gender');

// ---------------------------------------------------------------------------------------------------------------------
// Tabelle um Typen erweitern
$GLOBALS['TCA']['tt_address']['ctrl']['type'] = 'record_type';

// Neuen Typ Adresse hinzufuegen
$GLOBALS['TCA']['tt_address']['types'][\Ps\Xo\Domain\Model\Address::class] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem('tt_address', 'record_type', [
	'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.record_type.address',
	\Ps\Xo\Domain\Model\Address::class,
	'xo-ttaddress-address'
]);

// @todo: neue Palette fuer RecordType, hidden, Language hinzufuegen
// @todo: Feldefinitionen (Felder, Palette, Tabs)
$GLOBALS['TCA']['tt_address']['types'][\Ps\Xo\Domain\Model\Address::class]['showitem'] = 'record_type, name, hidden';