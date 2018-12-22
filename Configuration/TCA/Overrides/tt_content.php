<?php

// ---------------------------------------------------------------------------------------------------------------------
// Weitere Felder in TT-Content
$tmpXoTtContentColumns = [
	'tx_xo_variant' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_content.variant',
		'config' => [
			'type' => 'select',
			'renderType' => 'selectSingle',
			'items' => [
				['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0],
			],
		]
	],
	'tx_xo_header_class' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_content.header_class',
		'config' => [
			'type' => 'select',
			'renderType' => 'selectSingle',
			'items' => [
				['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0],
				['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout.I.1', 1],
				['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout.I.2', 2],
				['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout.I.3', 3],
				['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout.I.4', 4],
				['LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout.I.5', 5],
			],
		]
	],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tmpXoTtContentColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'frames', 'tx_xo_variant', 'after:frame_class');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'headers', 'tx_xo_header_class', 'after:header_layout');

// ---------------------------------------------------------------------------------------------------------------------
// Zuruecksetzen der Werte Space Before / After, damit diese komplett ueber PageTs befuellt werden koennen
$GLOBALS['TCA']['tt_content']['columns']['space_before_class']['config']['items'] = [];
$GLOBALS['TCA']['tt_content']['columns']['space_after_class']['config']['items'] = [];