<?php
namespace STI\SchemaInjector\Controller;

use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \TYPO3\CMS\Core\Utility\DebugUtility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Stefan Haberl <stefan.haberl@student.uibk.ac.at>, STI
 *           Mahtias Meinschad <mathias.meinschad@student.uibk.ac.at>, STI
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * InjectorController
 */
class InjectorController extends ActionController
{

    /**
     * action main
     * @return void
     */
    public function mainAction()
    {
        $pages = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',         // SELECT ...
            'tx_schemainjector_domain_model_injector',     // FROM ...
            ''                                              // WHERE
        );
        $page_names_query = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'inject_file_name',         // SELECT ...
            'tx_schemainjector_domain_model_injector',     // FROM ...
            '',
            'inject_file_name'
        );
        $page_names = array();
        foreach($page_names_query as $elem) {
            array_push($page_names, $elem[inject_file_name]);
        }
        $this->view->assign('pages', $pages);
        $this->view->assign('page_names', $page_names);

        $this->createFolder();
    }

    /**
     * action frontend
     * @return void
     */
    public function frontendAction() {
        // TODO: delete this action, if not needed
        DebugUtility::debug('frontendAction reached', ':)');
        // $GLOBALS['TSFE'] could be accessed here
        //$this->view->render();
    }

    public function uploadFileAction() {
        //DebugUtility::debug($_FILES['tx_schemainjecotr_injector']['name']['file'],'');

        $file = $this->request->getArgument('file');
        $existingFile = $this->request->getArgument('$existingFile');

        $pages = $this->request->getArgument('pages');
        $pageArray = explode(" ", $pages);

        $validFile = substr($file[name], -4) == "json";
        $validPages =  $this->pageCheck($pageArray);


        if(!$validPages) {
            $this->addFlashMessage(
                $messageBody = 'Unfortunatly your file could not be uploaded, please select a valid .json file and valid pages to inject',
                $messageTitle = 'Upload failed',
                $severity = \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                $storeInSession = TRUE
            );
            $this->redirect('main');
        } else if(!$validFile) {
            $page_names_query = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                'inject_file_name',         // SELECT ...
                'tx_schemainjector_domain_model_injector',     // FROM ...
                '',
                'inject_file_name'
            );
            $page_names = array();
            foreach($page_names_query as $elem) {
                array_push($page_names, $elem[inject_file_name]);
            }
            $this->addEntryToDatabase($page_names[$existingFile], $pageArray);
            $this->redirect('main');
        } else {
            $folderName = 'uploads';
            $tmpName = $file['name'];
            $tmpFile = $file['tmp_name'];

            $storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
            $storage = $storageRepository->findByUid('1'); //this is the fileadmin storage
            //get the storage folder
            $targetFolder = $storage->getFolder($folderName);

            //file name, be sure that this is unique
            $newFileName = $tmpName;

            //check if file already exists
            if($storage->hasFileInFolder($newFileName, $targetFolder)) {
                $this->addFlashMessage(
                    $messageBody = 'Unfortunately your file upload failed, because this file already exists',
                    $messageTitle = 'Upload failed',
                    $severity = \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                    $storeInSession = TRUE
                );
                $this->redirect('main');
            } else {
                //build sys_file
                $storage->addFile($tmpFile, $targetFolder, $newFileName);
                $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();
                $this->addEntryToDatabase($newFileName, $pageArray);

                $this->addFlashMessage(
                    $messageBody = 'Your file was uploaded successfully',
                    $messageTitle = 'Upload successful',
                    $severity = \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                    $storeInSession = TRUE
                );
                $this->redirect('main');
            }
        }
    }

    public function pageCheck($pageArray) {
        $check = true;
        $pages = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',         // SELECT ...
            'pages',     // FROM ...
            ''           // WHERE
        );

        $i = 0;
        foreach($pages as $page) {
            $pageTitleArray[$i] = $page[title];
            $i += 1;
        }

        foreach($pageArray as $page) {
            if(!in_array($page,$pageTitleArray) && !strpos($page, '%')) {
                $check = false;
                break;
            }
        }
        return $check;
    }

    public function addEntryToDatabase($file, $pagesToInsert) {
        foreach($pagesToInsert as $page) {
            $pagesFromDB = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                "*",
                "pages",
                "title like '$page'"
            );

            foreach($pagesFromDB as $actualPage) {
                // only insert if no entry exists!
                if($this->noEntryExists($actualPage, $file)) {
                    $GLOBALS['TYPO3_DB']->exec_INSERTquery(
                        'tx_schemainjector_domain_model_injector',
                        array(pid => $actualPage[pid],
                            inject_page_id => $actualPage[uid],
                            inject_page_name => $actualPage[title],
                            inject_file_name => $file)
                    );
                }
            }
        }

        /*
        $pages = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',         // SELECT ...
            'pages',     // FROM ...
            ''           // WHERE
        );

        $i = 0;
        foreach($pages as $page) {
            $pageArray[$i][0] = $page[uid];
            $pageArray[$i][1] = $page[title];
            $i += 1;
        }
        foreach($pageArray as $page) {
            if(in_array($page[1],$pagesToInsert) AND $this->noEntryExists($page[1], $file)) {
                $GLOBALS['TYPO3_DB']->exec_INSERTquery(
                    'tx_schemainjector_domain_model_injector',
                    array(pid => 1,
                          inject_page_id => $page[0],
                          inject_page_name => $page[1],
                          inject_file_name => $file)
                );
            }
        }*/
    }

    public function noEntryExists($page, $file) {
        $pages = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',         // SELECT ...
            'tx_schemainjector_domain_model_injector',     // FROM ...
            'inject_file_name = "'.$file.'" AND inject_page_name = "'.$page.'"'       // WHERE ...
        );

        return mysqli_num_rows($pages) ? false : true;
    }

    public function deleteEntryAction() {
        $file = $this->request->getArgument('file_name');
        $page = $this->request->getArgument('page_name');

        $delValues = 'inject_page_name = "'.$page.'" AND inject_file_name = "'.$file.'"';
        $GLOBALS['TYPO3_DB']->exec_DELETEquery(
            'tx_schemainjector_domain_model_injector',
            $delValues
        );

        //If the file is not used anymore we can delete it
        $query = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
            'inject_file_name',         // SELECT ...
            'tx_schemainjector_domain_model_injector',     // FROM ...
            'inject_file_name = "'.$file.'"'
        );
        if(!$query) {
            $storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
            $storage = $storageRepository->findByUid('1');
            $targetFolder = $storage->getFolder("uploads");
            $fileObject = $storage->getFileInFolder($file, $targetFolder);
            $storage->deleteFile($fileObject);
        }
        $this->redirect('main');
    }

    public function listPagesAction() {

    }

    private function createFolder() {
        $folderName = 'uploads';
        $storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
        $storage = $storageRepository->findByUid('1'); //this is the fileadmin storage
        //check if the folder already exists, if not create it!
        if(!$storage->hasFolder($folderName)) {
            $storage->createFolder($folderName);
        }
    }
}