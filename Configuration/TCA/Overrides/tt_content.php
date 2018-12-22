<?php

// ---------------------------------------------------------------------------------------------------------------------
// Weitere Felder in TT-Content
$tmpXoTtContentColumns = array(
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
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tmpXoTtContentColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'frames', 'tx_xo_variant', 'after:frame_class');

// ---------------------------------------------------------------------------------------------------------------------
// Zuruecksetzen der Werte Space Before / After, damit diese komplett ueber PageTs befuellt werden koennen
$GLOBALS['TCA']['tt_content']['columns']['space_before_class']['config']['items'] = [];
$GLOBALS['TCA']['tt_content']['columns']['space_after_class']['config']['items'] = [];