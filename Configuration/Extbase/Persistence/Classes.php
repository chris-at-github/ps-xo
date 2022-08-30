<?php

// @see: https://docs.typo3.org/m/typo3/book-extbasefluid/master/en-us/6-Persistence/4-use-foreign-data-sources.html
return [
	\Ps\Xo\Domain\Model\Address::class => [
		'tableName' => 'tt_address',
		'properties' => [
			'schemaOrgMedia' => [
				'fieldName' => 'tx_xo_schemaorg_media'
			],
			'directors' => [
				'fieldName' => 'tx_xo_directors'
			],
			'commercialRegister' => [
				'fieldName' => 'tx_xo_commercial_register'
			],
			'registeredOffice' => [
				'fieldName' => 'tx_xo_registered_office'
			],
			'vatNumber' => [
				'fieldName' => 'tx_xo_vat_number'
			],
			'openingHours' => [
				'fieldName' => 'tx_xo_opening_hours'
			],
			'openingHoursDescription' => [
				'fieldName' => 'tx_xo_opening_hours_description'
			],
			'instagram' => [
				'fieldName' => 'tx_xo_instagram'
			],
			'youtube' => [
				'fieldName' => 'tx_xo_youtube'
			],
			'additionalDescription' => [
				'fieldName' => 'tx_xo_additional_description'
			],
		]
	],
	\Ps\Xo\Domain\Model\Page::class => [
		'tableName' => 'pages',
		'properties' => [
			'noLink' => [
				'fieldName' => 'tx_xo_no_link'
			],
		]
	],
	\Ps\Xo\Domain\Model\Category::class => [
		'tableName' => 'sys_category',
		'properties' => [
			'link' => [
				'fieldName' => 'tx_xo_link'
			],
		]
	],
	\Ps\Xo\Domain\Model\Element::class => [
		'tableName' => 'tx_xo_domain_model_elements'
	],
];