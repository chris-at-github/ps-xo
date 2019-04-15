<?php

// ---------------------------------------------------------------------------------------------------------------------
// Weitere Felder in TT-Content
$tmpXoTtAddressColumns = [
	'record_type' => [
		'exclude' => 0,

		// @todo: saubere Benennung hinterlegen
		'label' => 'LLL:EXT:examples/Resources/Private/Language/locallang_db.xlf:tx_examples_dummy.record_type',
		'config' => [
			'type' => 'select',
			'renderType' => 'selectSingle',
			'items' => [
				['', 0]
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
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_address', 'name', 'record_type', 'after:gender');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_address', 'paletteHidden', 'tx_xo_content', 'after:hidden');

// ---------------------------------------------------------------------------------------------------------------------
// Tabelle um Typen erweitern
$GLOBALS['TCA']['tt_address']['ctrl']['type'] = 'record_type';

// Neuen Typ Adresse hinzufuegen
// @todo: FontAwesome Icon hinzufuegen
$GLOBALS['TCA']['tt_address']['types'][\Ps\Xo\Domain\Model\Address::class] = $GLOBALS['TCA']['tx_news_domain_model_news']['types']['0'];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem('tt_address', 'record_type', [
	'Address XYZ',
	\Ps\Xo\Domain\Model\Address::class
]);

// @todo: neue Palette fuer RecordType, hidden, Language hinzufuegen
// @todo: Feldefinitionen (Felder, Palette, Tabs)
$GLOBALS['TCA']['tt_address']['types'][\Ps\Xo\Domain\Model\Address::class]['showitem'] = 'record_type, name, hidden';