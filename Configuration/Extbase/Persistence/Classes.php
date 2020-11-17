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
		]
	],
];