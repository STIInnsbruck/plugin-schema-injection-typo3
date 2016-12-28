<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 14.12.16
 * Time: 11:12
 */
use \TYPO3\CMS\Core\Utility\DebugUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \STI\SchemaInjector\Domain\Repository\InjectorRepository;

class tx_schemainjector_fehook
{

    function performInjectionIncScript(&$params, &$that)
    {
        //DebugUtility::debug('performing injection1', 'hook works');

        $pid = $GLOBALS['TSFE']->id;
        $debugMsg = '';
        $debugMsg .= 'id ' . print_r($pid, TRUE) . chr(10);
        $debugMsg .= 'page ' . print_r($GLOBALS['TSFE']->page, TRUE) . chr(10);
        $debugMsg .= 'title ' . print_r($GLOBALS['TSFE']->title, TRUE) . chr(10);
        $debugMsg .= 'tree ' . print_r($GLOBALS['TSFE']->sys_page->getRootLine($pid), TRUE) . chr(10);

        //DebugUtility::debug($debugMsg, '');


        $this->performInjection($params['pObj']->content);


        //$repository = GeneralUtility::makeInstance(InjectorRepository::class);
        //$entities = $repository->findAll();

        //DebugUtility::debug( print_r($entities, TRUE), '');

    }

    function performInjectionNoIncScript(&$params, &$that)
    {
        //DebugUtility::debug('performing injection2', 'hook works');

    }

    private function performInjection(&$content)
    {
        $infoMessage = '<!-- schema.org -->' . chr(10);

        // read JSON-LD file
        $jsonText = '';


        // inject thos lines of code
        $injectedCode = $infoMessage;
        $injectedCode .= $jsonText;
        $content = str_replace('</head>', $injectedCode . '</head>', $content);

    }


}