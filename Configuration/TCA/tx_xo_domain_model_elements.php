<?php

return [
	'ctrl' => [
		'title' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'record_type',
		'versioningWS' => true,
		'languageField' => 'sys_language_uid',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		],
		'searchFields' => 'title, description',
		'iconfile' => 'EXT:xo/Resources/Public/Icons/xo-content-elements.svg'
	],
	'types' => [
		'0' => [
			'showitem' => '
				l10n_diffsource, record_type, --palette--;;header, link, description, media, thumbnail, files,
				--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:tabs.access,
				--palette--;;visibility,
				--palette--;;access'
		],
	],
	'palettes' => [
		'header' => [
			'showitem' => 'title, title_type,'
		],
		'visibility' => [
			'showitem' => 'hidden,',
		],
		'access' => [
			'showitem' => 'starttime,endtime,',
		],
	],
	'columns' => [
		'sys_language_uid' => [
			'exclude' => true,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.language',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'special' => 'languages',
				'items' => [
					[
						'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
						-1,
						'flags-multiple'
					]
				],
				'default' => 0,
			],
		],
		'l10n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => true,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'default' => 0,
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tx_xo_domain_model_elements',
				'foreign_table_where' => 'AND tx_xo_domain_model_elements.pid=###CURRENT_PID### AND tx_xo_domain_model_elements.sys_language_uid IN (-1,0)',
			],
		],
		'l10n_diffsource' => [
			'config' => [
				'type' => 'passthrough',
			],
		],
		't3ver_label' => [
			'label' => 'LLL:EXT:datamints_dachau/Resources/Private/Language/locallang_db.xlf:default_feeld.t3ver_label',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			],
		],
		'record_type' => [
			'exclude' => 0,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements.record_type',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0],
				]
			]
		],
		'hidden' => [
			'exclude' => true,
			'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config' => [
				'type' => 'check',
				'default' => 0
			]
		],
		'starttime' => [
			'exclude' => true,
			'behaviour' => [
				'allowLanguageSynchronization' => true
			],
			'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'size' => 13,
				'eval' => 'datetime,int',
				'default' => 0,
			],
		],
		'endtime' => [
			'exclude' => true,
			'behaviour' => [
				'allowLanguageSynchronization' => true
			],
			'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'size' => 13,
				'eval' => 'datetime,int',
				'default' => 0,
				'range' => [
					'upper' => mktime(0, 0, 0, 1, 1, 2038)
				],
			],
		],
		'title' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements.title',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required',
			],
		],
		'title_type' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements.title_type',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => [
					['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0],
				],
				'default' => 0,
			],
		],
		'description' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements.description',
			'config' => [
				'type' => 'text',
				'enableRichtext' => true,
				'richtextConfiguration' => 'xoMinimal',
				'fieldControl' => [
					'fullScreenRichtext' => [
						'disabled' => false,
					],
				],
				'eval' => 'trim',
			],
		],

		// ['media']['config']['overrideChildTca']['types']

		'media' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements.media',
			'l10n_mode' => 'exclude',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'media',
				[
					'appearance' => [
						'collapseAll' => 1,
						'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference',
					],
					'overrideChildTca' => [
						'types' => [
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
							\TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
								'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
							]
						]
					],
					'maxitems' => 1
				]
			),
		],
		'thumbnail' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements.thumbnail',
			'l10n_mode' => 'exclude',
			'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
				'thumbnail',
				[
					'appearance' => [
						'collapseAll' => 1,
						'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference',
					],
					'overrideChildTca' => [
						'types' => [
							'0' => [
								'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
							],
							\TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
								'showitem' => '
							--palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
							--palette--;;filePalette'
							],
						]
					],
					'maxitems' => 1
				]
			),
		],
		'files' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements.files',
			'config' =>
				\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
					'files',
					[
						'appearance' => [
							'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference'
						],
						'maxitems' => 9999
					]
				),
		],
		'link' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_elements.link',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputLink',
				'size' => 50,
				'max' => 1024,
				'eval' => 'trim',
				'fieldControl' => [
					'linkPopup' => [
						'options' => [
							'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_link_formlabel',
						],
					],
				],
				'softref' => 'typolink'
			]
		],
	],
];



