<?php

// ---------------------------------------------------------------------------------------------------------------------
// Weitere Felder in TT-Content
$tmpXoPagesColumns = [
	'tx_xo_flash' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_pages.flash',
		'config' => [
			'type' => 'inline',
			'foreign_table' => 'tt_content',
			'foreign_field' => 'tx_xo_page',
			'maxitems' => 1,
			'appearance' => [
				'collapseAll' => 1,
				'levelLinksPosition' => 'top',
				'showSynchronizationLink' => 1,
				'showPossibleLocalizationRecords' => 1,
				'showAllLocalizationLink' => 1
			],
		],
	],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tmpXoPagesColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages', 'media', '--linebreak--, tx_xo_flash', 'after:media');

// ---------------------------------------------------------------------------------------------------------------------
// Neuen Pagetyp Typolink
//(function() {
	$pageTypeTypolink = 120;

	// Add new page type as possible select item:
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
		'pages',
		'doktype',
		[
			'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_pages.pagetype.typolink',
			$pageTypeTypolink,
			'EXT:xo/Resources/Public/Icons/xo-page-typolink.svg'
		],
		'1',
		'after'
	);

	\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
		$GLOBALS['TCA']['pages'],
		[
			// add icon for new page type:
			'ctrl' => [
				'typeicon_classes' => [
					$pageTypeTypolink => 'xo-page-typolink',
				],
			],
			// add all page standard fields and tabs to your new page type
			'types' => [
				(string) $pageTypeTypolink => [
					'showitem' => $GLOBALS['TCA']['pages']['types'][\TYPO3\CMS\Frontend\Page\PageRepository::DOKTYPE_LINK]['showitem']
				]
			]
		]
	);
//});