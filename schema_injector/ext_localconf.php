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