<?php

if(!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Hook is called before Caching / pages on their way in the cache.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = \Ps\Xo\Service\PostProcessService::class . '->outputCache';

// Hook is called after Caching / pages with COA_/USER_INT objects.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = \Ps\Xo\Service\PostProcessService::class . '->outputNoCache';

// Konfiguration fuer den (CKE) Editor im Backend
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['xo'] = 'EXT:xo/Configuration/RTE/Xo.yaml';

// ---------------------------------------------------------------------------------------------------------------------
// Registrierung Icons
// @see: https://www.typo3lexikon.de/typo3-tutorials/core/systemextensions/core/imaging.html
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

$iconRegistry->registerIcon(
	'xo-content-address',
	\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
	['source' => 'EXT:xo/Resources/Public/Icons/xo-content-address.svg']
);

$iconRegistry->registerIcon(
	'xo-ttaddress-address',
	\TYPO3\CMS\Core\Imaging\IconProvider\FontawesomeIconProvider::class,
	['name' => 'map-marker']
);