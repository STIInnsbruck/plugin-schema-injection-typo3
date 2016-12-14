<?php

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'STI.' . $_EXTKEY,
    'SchemaInjector',
    // this array contains the Controller name (without suffix Controller) as key,
    //      and the action function name without suffix "Action"
    array(
        'Injector' => 'frontend',
    )/*,
    array(
        'Frontend' => '',
    )*/
);

if (TYPO3_MODE == 'FE') {
    require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'class.tx_schemainjector.php');
}

$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = 'EXT:' . $_EXTKEY . '/class.tx_schemainjector_fehook.php:&tx_schemainjector_fehook->performInjectionIncScript';
/*
 * hook is called before Caching!
 * => for modification of pages on their way in the cache.
 **/
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = 'EXT:'. $_EXTKEY .'/class.tx_schemainjector_fehook.php:&tx_schemainjector_fehook->performInjectionNoIncScript';
