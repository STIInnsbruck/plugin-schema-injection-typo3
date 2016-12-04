<?php

// Prevent against security hacks, if somebody tries to execute php code from outside
if(!defined('TYPO3_MODE'))
    die('Access denied!');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    $_EXTKEY,
    'JsonLdImporter',
    // this array contains the Controller name (without suffix Controller) as key,
    //      and the action function name without suffix "Action"
    array(
        'JSONLdTypoScript' => 'inject'
    )
);