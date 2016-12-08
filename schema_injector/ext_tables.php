<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

/*\TYPO3\CMS\Core\Utility\DebugUtility::debug(
    'ext_tables.php reached', 'Debug: ' . __FILE__ . ' in Line: ' . __LINE__
);*/


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $_EXTKEY,
    'SchemaInjector',
    'Schema Injector'
);

if (TYPO3_MODE === 'BE') {
	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'STI.' . $_EXTKEY,
		'web',	 // Make module a submodule of 'web'
		'injector',	// Submodule key
		'',						// Position
		array(
			'Injector' => 'main',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_injector.xlf',
		)
	);

}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Schema.org Injector');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_schemainjector_domain_model_injector', 'EXT:schema_injector/Resources/Private/Language/locallang_csh_tx_schemainjector_domain_model_injector.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_schemainjector_domain_model_injector');
