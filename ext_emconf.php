<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "Xo"
 ***************************************************************/
$EM_CONF[$_EXTKEY] = [
	'title' => 'PS Xo',
	'description' => '',
	'category' => 'plugin',
	'author' => 'Christian Pschorr',
	'author_email' => 'pschorr.christian@gmail.com',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '1.0.11',
	'constraints' => [
		'depends' => [
			'typo3' => '10.4.0-10.4.99',
			'tt_address' => '5.2.0-5.10.99',
			'scriptmerger' => '7.1.0-7.10.99',
			'container_columns' => '1.0.0-1.0.99',
		],
		'conflicts' => [],
		'suggests' => [],
	],
	'autoload' => [
		'psr-4' => [
			'Ps\\Xo\\' => 'Classes',
		],
	],
];