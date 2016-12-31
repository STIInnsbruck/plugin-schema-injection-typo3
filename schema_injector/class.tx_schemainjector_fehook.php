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
        $currentPageId = $GLOBALS['TSFE']->id;
        $currentPageCategories = NULL;

        $sqlSelectStatement = 'inject_file_name';
        $sqlTableName = 'tx_schemainjector_domain_model_injector';
        $sqlWhereStatement = 'inject_page_id = ' . $currentPageId;

        // read from database
        $dbEntries = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            $sqlSelectStatement, $sqlTableName, $sqlWhereStatement
        );

        if(!isset($dbEntries) || sizeof($dbEntries) <= 0) {
            DebugUtility::debug('nothing to inject', '');

            return; // do nothing in this hook
        } else {
            $resultString = 'Injected files for this page: ' . chr(10);
            $jsonFileContent = '';
            foreach($dbEntries as $res) {
                $resultString .= $res['inject_file_name'] . ' / ';
                $jsonFileContent .= $this->readJSONFile($res['inject_file_name']);
            }

            $this->performInjection($params['pObj']->content, $resultString . $jsonFileContent);
            return;
        }
    }

    function performInjectionNoIncScript(&$params, &$that)
    {
        //DebugUtility::debug('performing injection2', 'hook works');

    }

    private function readJSONFile($fileName) {
        $storageRepository = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\StorageRepository::class);
        $storage = $storageRepository->findByUid('1'); //this is the fileadmin storage
        //get the storage folder
        $targetFolder = $storage->getFolder('uploads');

        //check if file already exists
        if($storage->hasFileInFolder($fileName, $targetFolder)) {
            //DebugUtility::debug('file ' . $fileName . 'was found', '');
            return ''; // TODO: add correct return statement
        }

        //DebugUtility::debug('file ' . $fileName . 'was NOT found', '');
        return '';
    }

    private function performInjection(&$content, $codeToInject)
    {
        $injectionContent = '<!-- schema.org -->' . chr(10);
        if($codeToInject != NULL)
            $injectionContent .= '<span style="color:green; font-weight: bold; font-size: x-large;">' . $codeToInject . '</span>';

        $content = str_replace('</head>', $injectionContent . '</head>', $content);
    }
}