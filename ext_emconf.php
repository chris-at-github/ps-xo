<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "play"
 ***************************************************************/
$EM_CONF[$_EXTKEY] = [
	'title' => 'PS Go',
	'description' => '',
	'category' => 'plugin',
	'author' => 'Christian Pschorr',
	'author_email' => 'pschorr.christian@gmail.com',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '0.0.1',
	'constraints' => [
		'depends' => [
			'typo3' => '9.5.0-9.5.99',
		],
		'conflicts' => [],
		'suggests' => [],
	],
	'autoload' => [
		'psr-4' => [
			'Ps\\Go\\' => 'Classes',
		],
	],
];
