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
	'tx_xo_no_frame' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_content.no_frame',
		'config' => [
			'type' => 'check',
			'items' => [
				'1' => [
					'0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
				]
			],
			'default' => 0,
		]
	],
	'tx_xo_file' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_content.file',
		'config' =>
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'tx_xo_file',
				[
					'appearance' => [
						'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
					],
					'maxitems' => 9999
				]
			),
	],
	'tx_xo_address' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_content.address',
		'config' => [
			'type' => 'inline',
			'foreign_table' => 'tt_address',
			'foreign_field' => 'tx_xo_content',
			'foreign_sortby' => 'sorting',
			'foreign_label' => 'name',
			'maxitems' => 999,
			'appearance' => [
				'collapseAll' => 1,
				'expandSingle' => 1,
				'showAllLocalizationLink' => 1,
				'showSynchronizationLink' => 1,
				'showPossibleLocalizationRecords' => 1,
				'showRemovedLocalizationRecords' => 1,
				'newRecordLinkAddTitle' => 1
			],
		]
	],
	'tx_xo_elements' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_content.tx_xo_elements',
		'config' => [
			'type' => 'inline',
			'foreign_table' => 'tx_xo_domain_model_elements',
			'foreign_field' => 'tx_xo_content',
			'foreign_sortby' => 'sorting',
			'foreign_label' => 'title',
			'maxitems' => 999,
			'appearance' => [
				'collapseAll' => 1,
				'expandSingle' => 1,
				'showAllLocalizationLink' => 1,
				'showSynchronizationLink' => 1,
				'showPossibleLocalizationRecords' => 1,
				'showRemovedLocalizationRecords' => 1,
				'newRecordLinkAddTitle' => 1
			],
		]
	]
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tmpXoTtContentColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'frames', 'tx_xo_no_frame, --linebreak--', 'before:layout');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'frames', 'tx_xo_variant', 'after:frame_class');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'headers', 'tx_xo_header_class', 'after:header_layout');

// ---------------------------------------------------------------------------------------------------------------------
// HTML-Erweiterungen von TT-Content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_html.title',
		'xo_html',
		'content-special-html'
	),
	'CType',
	'xo_html'
);

$GLOBALS['TCA']['tt_content']['types']['xo_html'] = [
	'showitem' => '
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,tx_xo_file,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
	',
	'columnsOverrides' => [
		'tx_xo_file' => [
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'tx_xo_file',
				[
					'appearance' => [
						'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
					],
					'maxitems' => 1
				],
				'html'
			),
		]
	]
];

// ---------------------------------------------------------------------------------------------------------------------
// Einfache Bildgalerie ueber TT-Content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_gallery.title',
		'xo_gallery',
		'content-image'
	),
	'CType',
	'xo_gallery'
);

$GLOBALS['TCA']['tt_content']['types']['xo_gallery'] = [
	'showitem' => '
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,tx_xo_file,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
	',
	'columnsOverrides' => [
		'tx_xo_file' => [
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_gallery.tx_xo_file',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'tx_xo_file',
				[
					'appearance' => [
						'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
					],
					'maxitems' => 999
				],
				$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
			),
		]
	]
];

$GLOBALS['TCA']['tt_content']['types']['xo_gallery']['columnsOverrides']['tx_xo_file']['config']['overrideChildTca']['types'] = [
	'0' => [
		'showitem' => '
			--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			--palette--;;filePalette'
	],
	\TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
		'showitem' => '
			--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			--palette--;;filePalette'
	],
	\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
		'showitem' => '
			--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			--palette--;;filePalette'
	],
	\TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
		'showitem' => '
			--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			--palette--;;filePalette'
	],
	\TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
		'showitem' => '
			--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			--palette--;;filePalette'
	],
	\TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
		'showitem' => '
			--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
			--palette--;;filePalette'
	]
];

// @see: https://www.clickstorm.de/blog/crop-funktion-fuer-bilder-in-typo3-8-7/
$GLOBALS['TCA']['tt_content']['types']['xo_gallery']['columnsOverrides']['tx_xo_file']['config']['overrideChildTca']['columns']['crop']['config']['cropVariants'] = [
	'fullsize' => [
		'title' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_crop_variant.fullsize',
		'allowedAspectRatios' => [
			'NaN' => [
				'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
				'value' => 0.0
			],
		],
		'selectedRatio' => 'NaN',
	],
	'thumbnail' => [
		'title' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_crop_variant.thumbnail',
		'allowedAspectRatios' => [
			'NaN' => [
				'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_wizards.xlf:imwizard.ratio.free',
				'value' => 0.0
			],
		],
		'selectedRatio' => 'NaN',
	],
];

// ---------------------------------------------------------------------------------------------------------------------
// Address Module von TT-Content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_address.title',
		'xo_address',
		'xo-content-address'
	),
	'CType',
	'xo_address'
);

$GLOBALS['TCA']['tt_content']['types']['xo_address'] = [
	'showitem' => '
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,tx_xo_address,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
	',
];

// ---------------------------------------------------------------------------------------------------------------------
// Teaser Module von TT-Content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_teaser.title',
		'xo_teaser',
		'content-menu-section'
	),
	'CType',
	'xo_teaser'
);

$GLOBALS['TCA']['tt_content']['types']['xo_teaser'] = [
	'showitem' => '
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,description,tx_xo_elements,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
	',
];

// ---------------------------------------------------------------------------------------------------------------------
// Icon Text Module von TT-Content
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_icon_text.title',
		'xo_icon_text',
		'content-textpic'
	),
	'CType',
	'xo_icon_text'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	'*',
	'FILE:EXT:xo/Configuration/FlexForms/ContentElements/IconText.xml',
	'xo_icon_text'
);

$GLOBALS['TCA']['tt_content']['types']['xo_icon_text'] = [
	'showitem' => '
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,description,tx_xo_elements,pi_flexform,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
	',
];

//$GLOBALS['TCA']['tt_content']['types']['xo_icon_text']['columnsOverrides']['tx_xo_elements']['config']['overrideChildTca']['types']['0'] = [
//	'showitem' => '
//		l10n_diffsource, hidden, title, description, media,
//		--linebreak--,
//		--palette--;;paletteDate'
//];

// ---------------------------------------------------------------------------------------------------------------------
// Zuruecksetzen der Werte Space Before / After, damit diese komplett ueber PageTs befuellt werden koennen
$GLOBALS['TCA']['tt_content']['columns']['space_before_class']['config']['items'] = [];
$GLOBALS['TCA']['tt_content']['columns']['space_after_class']['config']['items'] = [];