<?php

if(!defined('TYPO3_MODE')) {
	die('Access denied.');
}

call_user_func(function($_EXTKEY) {
	// Register plugin für Contact
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
		'Ps.' . $_EXTKEY,
		'Address',
		'LLL:EXT:xo/Resources/Private/Language/locallang_plugin.xlf:tx_xo_address.title',
		'xo-ttaddress-address'
	);

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_xo_domain_model_elements');
}, $_EXTKEY);