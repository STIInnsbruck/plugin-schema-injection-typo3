<?php

    $EM_CONF[$_EXTKEY] = array(
        'title' => 'JSON-LD Importer',
        'description' => 'Add JSON-LD structured data to your website simply by adding tags to your TypoScript file',
        'category' => 'plugin',
        'author' => 'Mathias Meinschad and Stefan Haberl',
        'author_company' => 'Semantic Technology Institute Innsbruck, Austria',
        'author_email' => 'stefan.haberl@student.uibk.ac.at',
        'dependencies' => '',
        'state' => 'alpha',
        'clearCacheOnLoad' => '0',
        'version' => '1.0.0',
        'constraints' => array(
            'depends' => array(
                'typo3' => '6.2.0-8.99.99'
            )
        )
    );
?>