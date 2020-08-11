<?php

if(!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// ---------------------------------------------------------------------------------------------------------------------
// Plugins

// Configure plugins
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Ps.' . $_EXTKEY,
	'Address',
	[
		'Address' => 'record, map',
	],
	[]
);

// ---------------------------------------------------------------------------------------------------------------------
// Override Core Classes
// @see: https://app.asana.com/0/1184169373040457/1188160889734406/f
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Frontend\ContentObject\Menu\TextMenuContentObject::class] = [
	'className' => Ps\Xo\ContentObject\Menu\TextMenuContentObject::class
];

// ---------------------------------------------------------------------------------------------------------------------
// Hooks

// Hook is called before Caching / pages on their way in the cache.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = \Ps\Xo\Service\PostProcessService::class . '->outputCache';

// Hook is called after Caching / pages with COA_/USER_INT objects.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = \Ps\Xo\Service\PostProcessService::class . '->outputNoCache';

// Ausblendung von Unused Content Elements z.B. Flash Inhalte fuer Seiten und Inhalte
// @see: https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Feature-82213-NewHookToDetermineIfContentRecordIsUsedUnused.html
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['record_is_used'][] = \Ps\Xo\Service\BackendPageLayoutService::class . '->isContentUsed';

// ---------------------------------------------------------------------------------------------------------------------
// FCK Editor
// Konfiguration fuer den (CKE) Editor im Backend
$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['xo'] = 'EXT:xo/Configuration/RTE/Xo.yaml';

// ---------------------------------------------------------------------------------------------------------------------
// Icons

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