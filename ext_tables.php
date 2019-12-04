<?php

if(!defined('TYPO3_MODE')) {
	die('Access denied.');
}

call_user_func(function($_EXTKEY) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_xo_domain_model_elements');
}, $_EXTKEY);