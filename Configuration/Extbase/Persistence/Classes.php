<?php

// @see: https://docs.typo3.org/m/typo3/book-extbasefluid/master/en-us/6-Persistence/4-use-foreign-data-sources.html
return [
	\Ps\Xo\Domain\Model\Address::class => [
		'tableName' => 'tt_address',
		'properties' => [
			'schemaOrgType' => [
				'fieldName' => 'tx_xo_schemaorg_type'
			],
			'schemaOrgMedia' => [
				'fieldName' => 'tx_xo_schemaorg_media'
			]
		]
	],
];