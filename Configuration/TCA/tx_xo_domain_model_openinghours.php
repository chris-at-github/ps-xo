<?php
return [
	'ctrl' => [
		'title' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_openinghours',
		'label' => 'days',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',
		'versioningWS' => true,
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		],
		'searchFields' => '',
		'iconfile' => 'EXT:xo/Resources/Public/Icons/xo-opening-hours.svg'
	],
	'types' => [
		'1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, days, days_title, open_at, close_at, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
	],
	'columns' => [
		'sys_language_uid' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'special' => 'languages',
				'items' => [
					[
						'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
						-1,
						'flags-multiple'
					]
				],
				'default' => 0,
			],
		],
		'l10n_parent' => [
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'default' => 0,
				'items' => [
					['', 0],
				],
				'foreign_table' => 'tx_xo_domain_model_openinghours',
				'foreign_table_where' => 'AND {#tx_xo_domain_model_openinghours}.{#pid}=###CURRENT_PID### AND {#tx_xo_domain_model_openinghours}.{#sys_language_uid} IN (-1,0)',
			],
		],
		'l10n_diffsource' => [
			'config' => [
				'type' => 'passthrough',
			],
		],
		'hidden' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
			'config' => [
				'type' => 'check',
				'renderType' => 'checkboxToggle',
				'items' => [
					[
						0 => '',
						1 => '',
						'invertStateDisplay' => true
					]
				],
			],
		],
		'starttime' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'eval' => 'datetime,int',
				'default' => 0,
				'behaviour' => [
					'allowLanguageSynchronization' => true
				]
			],
		],
		'endtime' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'eval' => 'datetime,int',
				'default' => 0,
				'range' => [
					'upper' => mktime(0, 0, 0, 1, 1, 2038)
				],
				'behaviour' => [
					'allowLanguageSynchronization' => true
				]
			],
		],
		'days' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_openinghours.days',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectCheckBox',
				'items' => [
					['LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_date.monday', 'monday'],
					['LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_date.tuesday', 'tuesday'],
					['LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_date.wednesday', 'wednesday'],
					['LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_date.thursday', 'thursday'],
					['LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_date.friday', 'friday'],
					['LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_date.saturday', 'saturday'],
					['LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_date.sunday', 'sunday'],
				],
				'size' => 1,
				'mintems' => 1,
			],
		],
		'days_title' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_openinghours.days_title',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
			],
		],
		'open_at' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_openinghours.open_at',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'dbType' => 'time',
				'size' => 4,
				'eval' => 'time,null',
				'default' => null
			]
		],
		'close_at' => [
			'exclude' => true,
			'label' => 'LLL:EXT:xo/Resources/Private/Language/locallang_tca.xlf:tx_xo_domain_model_openinghours.close_at',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'dbType' => 'time',
				'size' => 4,
				'eval' => 'time,null',
				'default' => null
			]
		],
		'address' => [
			'config' => [
				'type' => 'passthrough',
			],
		],
	],
];
