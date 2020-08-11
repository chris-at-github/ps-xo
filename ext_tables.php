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

	// Neuen Seitentyp Typolink erstellen
	$pageTypeTypolink = 120;

	// Add new page type:
	$GLOBALS['PAGES_TYPES'][$pageTypeTypolink] = [
		'type' => 'web',
		'allowedTables' => '*',
	];

	// Provide icon for page tree, list view, ... :
	\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)
		->registerIcon(
			'xo-page-typolink',
			TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
			[
				'source' => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/xo-page-typolink.svg',
			]
		);

	// Allow backend users to drag and drop the new page type:
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
		'options.pageTree.doktypesToShowInNewPageDragArea := addToList(' . $pageTypeTypolink . ')'
	);
}, $_EXTKEY);