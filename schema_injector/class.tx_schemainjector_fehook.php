<?php
use \TYPO3\CMS\Core\Utility\GeneralUtility;

class tx_schemainjector_fehook
{
    protected $sqlTableName = 'tx_schemainjector_domain_model_injector';
    protected $sqlColumnNamePageId = 'inject_page_id';
    protected $sqlColumnNameFileName = 'inject_file_name';

    function performNotCached(&$params, &$that) {
        if(!$GLOBALS['TSFE']->isINTincScript()) {
            return;
        }
        $this->main($params, $that);
    }

    function performCached(&$params, &$that) {
        if($GLOBALS['TSFE']->isINTincScript()) {
            return;
        }
        $this->main($params, $that);
    }

    /**
     * performs the main injector task (reading database -> reading json files -> inject)
     * @param $params object
     * @param $that object not used at the moment
     */
    function main(&$params, &$that)
    {
        $currentPageId = $GLOBALS['TSFE']->id;

        // build up the sql query statements
        $sqlSelectStatement = $this->sqlColumnNameFileName;
        $sqlWhereStatement = "$this->sqlColumnNamePageId = $currentPageId";

        // read from database
        $dbEntries = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            $sqlSelectStatement, $this->sqlTableName, $sqlWhereStatement
        );

        if(!isset($dbEntries) || $GLOBALS['TYPO3_DB']->sql_num_rows($dbEntries) == 0) {
            // nothing to inject for this page ...
            return;
        } else {
            $jsonFileContent = '';

            foreach($dbEntries as $res)
                $jsonFileContent .= $this->readJSONFile($res[$this->sqlColumnNameFileName]);

            $this->performInjection($params['pObj']->content, $jsonFileContent);
        }
    }

    /**
     * reads a json file specified by fileName. It returns the complete string which is prepared for injection.
     * @param $fileName string specifies the file to be read
     * @return string formatted json-ld with <script> tags and html comment which prints errors and information to this file
     */
    private function readJSONFile($fileName) {
        $storageRepository = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\StorageRepository::class);
        $storage = $storageRepository->findByUid('1'); // access the 'fileAdmin' folder

        $htmlComment = "<!-- schema_injector: $fileName ";
        $jsonContent = '';
        $infoMsg = '';

        //get the storage folder
        $targetFolder = $storage->getFolder('uploads');

        if($storage->hasFileInFolder($fileName, $targetFolder)) {
            try {
                $jsonFile = $storage->getFileInFolder($fileName, $targetFolder);
                $jsonContent = $this->validateJSONFile($storage->getFileContents($jsonFile));
            } catch(\TYPO3\CMS\Core\Resource\Exception $e) {
                $infoMsg = ' could not read file ...';
            }
        } else {
            $infoMsg = ' file not found in folder uploads';
        }

        return $htmlComment . $infoMsg . ' -->' . chr(10) . $jsonContent . chr(10);
    }

    /**
     * simply checks, if the given jsonContent contains the necessary <script> tags. If not those tags are inserted
     * @param $jsonContent string with json content or js content
     * @return string the correct formatted string with <script> tags
     */
    private function validateJSONFile($jsonContent) {
        if(strlen($jsonContent) == 0)
            return $jsonContent;

        $scriptTagStart = '';
        $scriptTagEnd = '';
        // if there is a simple script tag, exchange it to the json-ld specific opening script tag
        if(strpos($jsonContent, '<script>') !== FALSE) {
            $jsonContent = str_replace('<script>', '<script type="application/ld+json">', $jsonContent);
        }
        // if there not already a json-ld specific opening script tag, insert it
        if(strpos($jsonContent, '<script type="application/ld+json">') === FALSE) {
            $scriptTagStart = '<script type="application/ld+json">';
        }
        // ensure there is a closing script tag
        if(strpos($jsonContent, '</script>') === FALSE) {
            $scriptTagEnd = '</script>';
        }
        return $scriptTagStart . $jsonContent . $scriptTagEnd;
    }

    /**
     * this function takes a pointer to the actual html content of the page rendered. It injects the string inside $codeToInject before the closing head tag
     * @param $content string the actual html content rendered
     * @param $codeToInject string to inject
     */
    private function performInjection(&$content, $codeToInject)
    {
        if(strlen($codeToInject) == 0)
            return;

        $content = str_replace('</head>', $codeToInject . '</head>', $content);
    }
}