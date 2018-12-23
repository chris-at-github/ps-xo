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