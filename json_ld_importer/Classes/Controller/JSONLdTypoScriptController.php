<?php

//namespace \JSONLDIMPORTER\
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class JSONLdTypoScriptController extends ActionController
{
    public function injectAction(&$params) {
        if(!isset($GLOBALS['TSFE']))
            die("TSFE not set!");

        $currentContent = $params['pObj']->content;
        $currentInject = $GLOBALS['TSFE']->config['config']['jsonld'];

        $newContent = str_replace('</head>', $currentInject . '</head>', $currentContent);
        $params['pObj']->content = $newContent;

        die('inside injectAction!');
    }




}
