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
	],
	'tx_xo_schemaorg_media' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.schemaorg.media',
		'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
			'tx_xo_schemaorg_media',
			[
				'appearance' => [
					'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
				],
				'maxitems' => 1
			],
			$GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
		),
	],
	'tx_xo_directors' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tx_xo_directors',
		'config' => [
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim',
		],
	],
	'tx_xo_commercial_register' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tx_xo_commercial_register',
		'config' => [
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim',
		],
	],
	'tx_xo_registered_office' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tx_xo_registered_office',
		'config' => [
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim',
		],
	],
	'tx_xo_vat_number' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tx_xo_vat_number',
		'config' => [
			'type' => 'input',
			'size' => 30,
			'eval' => 'trim',
		],
	],
	'tx_xo_opening_hours_description' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tx_xo_opening_hours_description',
		'config' => [
			'type' => 'text',
			'enableRichtext' => true,
			'richtextConfiguration' => 'xoMinimal',
			'fieldControl' => [
				'fullScreenRichtext' => [
					'disabled' => false,
				],
			],
			'cols' => 40,
			'rows' => 15,
			'eval' => 'trim',
		],
	],
	'tx_xo_opening_hours' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tx_xo_opening_hours',
		'config' => [
			'type' => 'inline',
			'foreign_table' => 'tx_xo_domain_model_openinghours',
			'foreign_field' => 'address',
			'foreign_sortby' => 'sorting',
			'maxitems' => 9999,
			'appearance' => [
				'collapseAll' => 1,
				'levelLinksPosition' => 'top',
				'showSynchronizationLink' => 1,
				'showPossibleLocalizationRecords' => 1,
				'useSortable' => 1,
				'showAllLocalizationLink' => 1
			],
		],
	],
	'tx_xo_instagram' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tx_xo_instagram',
		'config' => [
			'type' => 'input',
			'size' => 40,
			'eval' => 'trim',
		],
	],
	'tx_xo_youtube' => [
		'exclude' => true,
		'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tx_xo_youtube',
		'config' => [
			'type' => 'input',
			'size' => 40,
			'eval' => 'trim',
		],
	],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_address', $tmpXoTtAddressColumns);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('tt_address', 'name', 'record_type', 'before:gender');

// ---------------------------------------------------------------------------------------------------------------------
// Tabelle um Typen erweitern
$GLOBALS['TCA']['tt_address']['ctrl']['type'] = 'record_type';

// Neuen Typ Adresse hinzufuegen
$GLOBALS['TCA']['tt_address']['types'][\Ps\Xo\Domain\Model\Address::class] = $GLOBALS['TCA']['tt_address']['types']['0'];
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem('tt_address', 'record_type', [
	'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.record_type.address',
	\Ps\Xo\Domain\Model\Address::class,
	'xo-ttaddress-address'
]);

// Neue Palette General hinzufuegen
$GLOBALS['TCA']['tt_address']['palettes']['xoGeneral'] = [
	'showitem' => 'record_type,'
];

$GLOBALS['TCA']['tt_address']['palettes']['xoAddress'] = [
	'showitem' => 'address, --linebreak--, city, zip, country,'
];

$GLOBALS['TCA']['tt_address']['palettes']['xoContact'] = [
	'showitem' => 'email, --linebreak--, phone, mobile, fax, --linebreak--, www,'
];

$GLOBALS['TCA']['tt_address']['palettes']['xoLegal'] = [
	'showitem' => 'tx_xo_directors, tx_xo_commercial_register, tx_xo_registered_office, tx_xo_vat_number,'
];

$GLOBALS['TCA']['tt_address']['palettes']['xoSeo'] = [
	'showitem' => 'tx_xo_schemaorg_media,'
];

$GLOBALS['TCA']['tt_address']['palettes']['xoSocial'] = [
	'showitem' => 'facebook, --linebreak--, twitter, --linebreak--, linkedin, --linebreak--, tx_xo_instagram, --linebreak--, tx_xo_youtube,'
];

$GLOBALS['TCA']['tt_address']['palettes']['xoOpeningHours'] = [
	'showitem' => 'tx_xo_opening_hours, --linebreak--, tx_xo_opening_hours_description, '
];

$GLOBALS['TCA']['tt_address']['types'][\Ps\Xo\Domain\Model\Address::class]['showitem'] = '
		--palette--;xoGeneral, record_type, name, description, image,
		--palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.address;xoAddress,
		--palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.contact;xoContact,
		--palette--;LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.palette.legal;xoLegal,
	--div--;LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tab.map,
		--palette--;LLL:EXT:tt_address/Resources/Private/Language/locallang_db.xlf:tt_address_palette.coordinates;coordinates,
	--div--;LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tab.opening_hours,
		--palette--;;xoOpeningHours,
	--div--;LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tab.seo,
		--palette--;;xoSeo,
	--div--;LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_tt_address.tab.social,
		--palette--;;xoSocial,		
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
		--palette--;;language,
	--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
		hidden,
	--div--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_category.tabs.category,
		categories
';

$GLOBALS['TCA']['tt_address']['types'][\Ps\Xo\Domain\Model\Address::class]['columnsOverrides']['name'] = [
	'config' => [
		'readOnly' => false,
	]
];

$GLOBALS['TCA']['tt_address']['types'][\Ps\Xo\Domain\Model\Address::class]['columnsOverrides']['description'] = [
	'config' => [
		'richtextConfiguration' => 'xoDefault',
	]
];

//'config' => [
//	'type' => 'text',
//	'enableRichtext' => true,
//	'richtextConfiguration' => 'xoMinimal',
//	'fieldControl' => [
//		'fullScreenRichtext' => [
//			'disabled' => false,
//		],
//	],
//	'cols' => 40,
//	'rows' => 15,
//	'eval' => 'trim',
//],

$GLOBALS['TCA']['tt_address']['types'][\Ps\Xo\Domain\Model\Address::class]['columnsOverrides']['facebook'] = [
	'config' => [
		'size' => 40,
	]
];

$GLOBALS['TCA']['tt_address']['types'][\Ps\Xo\Domain\Model\Address::class]['columnsOverrides']['twitter'] = [
	'config' => [
		'size' => 40,
	]
];

$GLOBALS['TCA']['tt_address']['types'][\Ps\Xo\Domain\Model\Address::class]['columnsOverrides']['linkedin'] = [
	'config' => [
		'size' => 40,
	]
];