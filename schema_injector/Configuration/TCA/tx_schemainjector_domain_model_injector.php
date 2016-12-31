<?php
return array(
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