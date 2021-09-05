<?php

call_user_func(function($_EXTKEY) {

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
		'tx_xo_no_breadcrumb' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_pages.no_breadcrumb',
			'config' => [
				'type' => 'check',
				'items' => [
					'1' => [
						'0' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled'
					]
				],
				'default' => 0,
			]
		],
		'tx_xo_no_sticky' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_pages.no_sticky',
			'config' => [
				'type' => 'check',
				'items' => [
					'1' => [
						'0' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled'
					]
				],
				'default' => 0,
			]
		],
		'tx_xo_no_link' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_pages.no_link',
			'config' => [
				'type' => 'check',
				'renderType' => 'checkboxToggle',
				'items' => [
					[
						0 => '',
						1 => '',
						'invertStateDisplay' => false
					]
				],
			]
		],
		'tx_xo_breadcrumb_hidden' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_pages.breadcrumb_hidden',
			'config' => [
				'type' => 'check',
				'items' => [
					'1' => [
						'0' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.enabled'
					]
				],
				'default' => 0,
			]
		],
		'tx_xo_navigation_layout' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_pages.tx_xo_navigation_layout',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['', 0],
				],
				'size' => 1,
				'maxitems' => 1,
				'eval' => ''
			],
		],
		'tx_xo_navigation_content' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_pages.tx_xo_navigation_content',
			'config' => [
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'tt_content',
				'foreign_table' => 'tt_content',
				'MM' => 'tx_xo_pages_content_mm',
				'maxitems' => 3,
				'size' => 4,
			],
		],
	];

	// Neue Palette General hinzufuegen
	$GLOBALS['TCA']['pages']['palettes']['xoBreadcrumb'] = [
		'showitem' => 'tx_xo_no_breadcrumb, tx_xo_breadcrumb_hidden,'
	];

	$GLOBALS['TCA']['pages']['palettes']['xoSticky'] = [
		'showitem' => 'tx_xo_no_sticky,'
	];

	$GLOBALS['TCA']['pages']['palettes']['xoNavigation'] = [
		'showitem' => 'tx_xo_navigation_layout, --linebreak--, tx_xo_navigation_content,'
	];

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tmpXoPagesColumns);

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', '--palette--;LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_pages.palette.breadcrumb;xoBreadcrumb,', '', 'after:layout');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', '--palette--;LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_pages.palette.sticky;xoSticky,', '', 'after:layout');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', '--palette--;LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_pages.palette.navigation;xoNavigation,', '', 'after:layout');

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages', 'media', '--linebreak--, tx_xo_flash', 'after:media');
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages', 'miscellaneous', 'tx_xo_no_link', 'after:no_search');

	// -------------------------------------------------------------------------------------------------------------------
	// Neuen Pagetyp Typolink
	$pageTypeTypolink = 120;

	// Add new page type:
	$GLOBALS['PAGES_TYPES'][$pageTypeTypolink] = [
		'type' => 'web',
		'allowedTables' => '*',
	];

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

			'types' => [
				(string) $pageTypeTypolink => [
					'showitem' => $GLOBALS['TCA']['pages']['types'][\TYPO3\CMS\Frontend\Page\PageRepository::DOKTYPE_LINK]['showitem'],
					'columnsOverrides' => [
						'url' => [
							'config' => [
								'renderType' => 'inputLink',
								'size' => 50,
								'max' => 1024,
								'fieldControl' => [
									'linkPopup' => [
										'options' => [
											'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_link_formlabel',
										],
									],
								],
								'softref' => 'typolink'
							]
						]
					]
				]
			]
		]
	);

}, 'xo');