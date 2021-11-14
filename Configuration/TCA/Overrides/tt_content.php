<?php

// ---------------------------------------------------------------------------------------------------------------------
// Weitere Felder in TT-Content
$tmpXoTtContentColumns = [
	'tx_xo_variant' => [
		'exclude' => true,
		'l10n_mode' => 'exclude',
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
		'l10n_mode' => 'exclude',
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
		'l10n_mode' => 'exclude',
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
			'foreign_field' => 'foreign_uid',
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
	],
	'tx_xo_flash' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_content.flash',
		'config' => [
			'type' => 'inline',
			'foreign_table' => 'tt_content',
			'foreign_field' => 'tx_xo_parent',
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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tmpXoTtContentColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'frames', 'tx_xo_no_frame, --linebreak--', 'before:layout');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'frames', 'tx_xo_variant', 'after:frame_class');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_content', 'headers', 'tx_xo_header_class', 'after:header_layout');


// ---------------------------------------------------------------------------------------------------------------------
// Weitere Paletten in TT-Content
$GLOBALS['TCA']['tt_content']['palettes']['xoHeader'] = [
	'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header',
	'showitem' => 'header, --linebreak--, header_layout, tx_xo_header_class, --linebreak--, header_link'
];

$GLOBALS['TCA']['tt_content']['palettes']['xoImageAdjustment'] = [
	'label' => 'LLL:EXT:frontend/Resources/Private/Language/Database.xlf:tt_content.palette.mediaAdjustments',
	'showitem' => 'imagewidth, imageheight,'
];

$GLOBALS['TCA']['tt_content']['palettes']['xoImageGallery'] = [
	'label' => 'LLL:EXT:frontend/Resources/Private/Language/Database.xlf:tt_content.palette.gallerySettings',
	'showitem' => 'imageorient,'
];

$GLOBALS['TCA']['tt_content']['palettes']['xoImageHidden'] = [
	'showitem' => 'imagecols',
	'isHiddenPalette' => 1
];

$GLOBALS['TCA']['tt_content']['palettes']['xoFlash'] = [
	'showitem' => 'tx_xo_flash,'
];


// ---------------------------------------------------------------------------------------------------------------------
// Header Modul
$GLOBALS['TCA']['tt_content']['types']['text']['showitem'] .= ', --palette--;;xoFlash,';
$GLOBALS['TCA']['tt_content']['columns']['subheader']['config'] = [
	'type' => 'text',
	'cols' => 50,
	'rows' => 3,
	'eval' => 'trim',
];


// ---------------------------------------------------------------------------------------------------------------------
// Text Modul
$GLOBALS['TCA']['tt_content']['types']['text']['showitem'] .= ', --palette--;;xoFlash,';


// ---------------------------------------------------------------------------------------------------------------------
// Image-Text Modul

// Showitem
$GLOBALS['TCA']['tt_content']['types']['textpic']['showitem'] = '
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
		--palette--;;xoImageHidden,
		--palette--;;general, 
		--palette--;;headers, bodytext;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:bodytext_formlabel, 
	--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.images, 
		image, 
		--palette--;;xoImageAdjustment,
		--palette--;;xoImageGallery, --linebreak--, pi_flexform,
	--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance, 
		--palette--;;frames, 
		--palette--;;appearanceLinks, 
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, 
		--palette--;;language, 
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, 
		--palette--;;hidden, 
		--palette--;;access,
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories, 
	--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category, categories, 
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes, rowDescription, 
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended';

// Oeffnen im neuen Lightbox vorruebergehend ausgeblendet
// @see: https://app.asana.com/0/1184169373040457/1200211172517498/f

// Imageorient
$GLOBALS['TCA']['tt_content']['columns']['imageorient']['config']['default'] = 25;
$GLOBALS['TCA']['tt_content']['columns']['imageorient']['config']['items'] = [];

$GLOBALS['TCA']['tt_content']['types']['textpic']['columnsOverrides']['imageorient']['config']['items'] = [
	[
		'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageorient.I.9',
		25,
		'content-beside-text-img-right'
	],
	[
		'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageorient.I.10',
		26,
		'content-beside-text-img-left'
	]
];

// Flexform
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	'*',
	'FILE:EXT:xo/Configuration/FlexForms/ContentElements/TextMedia.xml',
	'textpic'
);

// Ueberschreiben von Felddefinitionen
$GLOBALS['TCA']['tt_content']['types']['textpic']['columnsOverrides']['image']['config']['maxitems'] = 2;
$GLOBALS['TCA']['tt_content']['types']['textpic']['columnsOverrides']['image']['l10n_mode'] = 'exclude';

$GLOBALS['TCA']['tt_content']['types']['textpic']['columnsOverrides']['imagecols']['config']['default'] = 1;

// Flexform Einstellungen anhand der Bildausrichtungen
$GLOBALS['TCA']['tt_content']['types']['textpic']['columnsOverrides']['imageorient']['onChange'] = 'reload';

$GLOBALS['TCA']['tt_content']['types']['textpic']['columnsOverrides']['pi_flexform']['displayCond'] = 'FIELD:imageorient:IN:25,26';
$GLOBALS['TCA']['tt_content']['types']['textpic']['columnsOverrides']['pi_flexform']['l10n_mode'] = 'exclude';

// ---------------------------------------------------------------------------------------------------------------------
// Image Modul

// Showitem
$GLOBALS['TCA']['tt_content']['types']['image']['showitem'] = '
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
		--palette--;;xoImageHidden,
		--palette--;;general, 
		--palette--;;headers, 
	--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.images, 
		image, 
		--palette--;;xoImageAdjustment,
		--palette--;;xoImageGallery,
	--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance, 
		--palette--;;frames, 
		--palette--;;appearanceLinks, 
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, 
		--palette--;;language, 
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, 
		--palette--;;hidden, 
		--palette--;;access,
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories, 
	--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category, categories, 
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes, rowDescription, 
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended';

// Oeffnen im neuen Lightbox vorruebergehend ausgeblendet
// @see: https://app.asana.com/0/1184169373040457/1200211172517498/f

// Imageorient
$GLOBALS['TCA']['tt_content']['types']['image']['columnsOverrides']['imageorient']['config']['items'] = [
	[
		'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageorient.I.0',
		0,
		'content-beside-text-img-above-center'
	],
	[
		'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageorient.I.1',
		1,
		'content-beside-text-img-above-right'
	],
	[
		'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:imageorient.I.2',
		2,
		'content-beside-text-img-above-left'
	],
];

// Ueberschreiben von Felddefinitionen
$GLOBALS['TCA']['tt_content']['types']['image']['columnsOverrides']['image']['config']['maxitems'] = 2;
$GLOBALS['TCA']['tt_content']['types']['image']['columnsOverrides']['imagecols']['config']['default'] = 1;

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

//// ---------------------------------------------------------------------------------------------------------------------
//// Address Module von TT-Content
//\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
//	array(
//		'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_address.record.title',
//		'xo_addressrecord',
//		'xo-content-address'
//	),
//	'CType',
//	'xo'
//);
//
//$GLOBALS['TCA']['tt_content']['types']['xo_addressrecord'] = [
//	'showitem' => '
//			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
//			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,tx_xo_address,
//		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
//			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
//		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
//			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
//			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
//		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
//	',
//];

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
// Slider
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_slider.title',
		'xo_slider',
		'content-textpic'
	),
	'CType',
	'xo_slider'
);

$GLOBALS['TCA']['tt_content']['types']['xo_slider'] = [
	'showitem' => '
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,description,tx_xo_elements,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
			--palette--;;hidden,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
	',
];

$GLOBALS['TCA']['tt_content']['types']['xo_slider']['columnsOverrides']['tx_xo_elements']['config']['overrideChildTca']['columns']['record_type']['config']['items'] = [
	['LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements.record_type.slider', 'slider']
];

$GLOBALS['TCA']['tt_content']['types']['xo_slider']['columnsOverrides']['tx_xo_elements']['config']['overrideChildTca']['types'] = [
	'slider' => [
		'showitem' => '
				l10n_diffsource, record_type, --palette--;;header, link, description, media,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
				--palette--;;visibility,
				--palette--;;access'
	],
];



// ---------------------------------------------------------------------------------------------------------------------
// Media Wall
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_media_wall.title',
		'xo_media_wall',
		'content-image'
	),
	'CType',
	'tx_xo_media_wall'
);

$GLOBALS['TCA']['tt_content']['types']['xo_media_wall'] = [
	'showitem' => '
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,bodytext,tx_xo_elements,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
			--palette--;;hidden,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
	',
];

$GLOBALS['TCA']['tt_content']['types']['xo_media_wall']['columnsOverrides']['bodytext']['config'] = [
	'enableRichtext' => true,
	'richtextConfiguration' => 'xoDefault',
];

$GLOBALS['TCA']['tt_content']['types']['xo_media_wall']['columnsOverrides']['tx_xo_elements']['config']['overrideChildTca']['columns']['title']['config']['eval'] = 'trim';

$GLOBALS['TCA']['tt_content']['types']['xo_media_wall']['columnsOverrides']['tx_xo_elements']['config']['overrideChildTca']['columns']['record_type']['config']['items'] = [
	['LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements.record_type.media_wall.image', 'media_wall_image'],
	['LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements.record_type.media_wall.video', 'media_wall_video']
];

$GLOBALS['TCA']['tt_content']['types']['xo_media_wall']['columnsOverrides']['tx_xo_elements']['config']['overrideChildTca']['columns']['record_type']['config']['default'] = 'media_wall_image';

$GLOBALS['TCA']['tt_content']['types']['xo_media_wall']['columnsOverrides']['tx_xo_elements']['config']['overrideChildTca']['types'] = [
	'media_wall_image' => [
		'showitem' => '
				l10n_diffsource, record_type, --palette--;;header, media,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
				--palette--;;visibility,
				--palette--;;access',
	],
	'media_wall_video' => [
		'showitem' => '
				l10n_diffsource, record_type, --palette--;;header, media, thumbnail,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
				--palette--;;visibility,
				--palette--;;access'
	],
];

// ---------------------------------------------------------------------------------------------------------------------
// Media Wall
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
	array(
		'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_logo.title',
		'xo_logo',
		'content-image'
	),
	'CType',
	'tx_xo_logo'
);

$GLOBALS['TCA']['tt_content']['types']['xo_logo'] = [
	'showitem' => '
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.general;general,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,image,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.appearance,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.frames;frames,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
			--palette--;;hidden,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.visibility;visibility,
			--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.access;access,
		--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.extended
	',
];

$GLOBALS['TCA']['tt_content']['types']['xo_logo']['columnsOverrides']['image']['label'] = 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_logo.field.image';
$GLOBALS['TCA']['tt_content']['types']['xo_logo']['columnsOverrides']['image']['config']['appearance']['collapseAll'] = 1;

// ---------------------------------------------------------------------------------------------------------------------
// Zuruecksetzen der Werte Space Before / After, damit diese komplett ueber PageTs befuellt werden koennen
$GLOBALS['TCA']['tt_content']['columns']['space_before_class']['config']['items'] = [];
$GLOBALS['TCA']['tt_content']['columns']['space_after_class']['config']['items'] = [];

// ---------------------------------------------------------------------------------------------------------------------
// Uebersetzungsverhalten von bestehenden Feldern anpassen
$GLOBALS['TCA']['tt_content']['columns']['header_layout']['l10n_mode'] = 'exclude';
$GLOBALS['TCA']['tt_content']['columns']['layout']['l10n_mode'] = 'exclude';
$GLOBALS['TCA']['tt_content']['columns']['frame_class']['l10n_mode'] = 'exclude';
$GLOBALS['TCA']['tt_content']['columns']['tx_xo_variant']['l10n_mode'] = 'exclude';
$GLOBALS['TCA']['tt_content']['columns']['space_before_class']['l10n_mode'] = 'exclude';
$GLOBALS['TCA']['tt_content']['columns']['space_after_class']['l10n_mode'] = 'exclude';
$GLOBALS['TCA']['tt_content']['columns']['sectionIndex']['l10n_mode'] = 'exclude';
$GLOBALS['TCA']['tt_content']['columns']['linkToTop']['l10n_mode'] = 'exclude';
$GLOBALS['TCA']['tt_content']['columns']['imageorient']['l10n_mode'] = 'exclude';
$GLOBALS['TCA']['tt_content']['columns']['imagewidth']['l10n_mode'] = 'exclude';
$GLOBALS['TCA']['tt_content']['columns']['imageheight']['l10n_mode'] = 'exclude';

// ---------------------------------------------------------------------------------------------------------------------
// Plugin Einstellungen

// Flexform Address Plugin
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['xo_addressrecord'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('xo_addressrecord', 'FILE:EXT:xo/Configuration/FlexForms/Plugins/AddressRecord.xml');