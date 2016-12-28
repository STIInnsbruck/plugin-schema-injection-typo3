<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'STI.' . $_EXTKEY,
    'SchemaInjector',
    'SchemaInjector'
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

$GLOBALS['TCA']['tx_schemainjector_domain_model_injector'] = array(
        'ctrl' => array (
            'title'	=> 'LLL:EXT:schema_injector/Resources/Private/Language/locallang_db.xlf:tx_schemainjector_domain_model_injector',
            'label' => 'name',
        ),
        'columns' => array(
            'inject_page_id' => array(
                'label' => 'Page Id',
                'config' => array(
                    'type' => 'input',
                    'size' => '20',
                    'eval' => 'trim,required'
                )
            ),
            'inject_file_name' => array(
                'label' => 'File name',
                'config' => array(
                    'type' => 'text',
                    'eval' => 'trim'
                )
            ),
        ),
        'types' => array(
            '0' => array('showitem' => 'inject_page_id, inject_file_name')
        )
);