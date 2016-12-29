<?php
namespace STI\SchemaInjector\Controller;

use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
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

    }

    /**
     * action frontend
     * @return void
     */
    public function frontendAction() {
        DebugUtility::debug('frontendAction reached', ':)');
        // $GLOBALS['TSFE'] could be accessed here
        //$this->view->render();
    }

    public function uploadFileAction() {
        //DebugUtility::debug($_FILES['tx_schemainjecotr_injector']['name']['file'],'');

        $file = $this->request->getArgument('file');
        $valid = substr($file[name], -4) == "json";

        if(!$valid) {
            $this->addFlashMessage(
                $messageBody = 'Unfortunatly your file could not be uploaded, please select a valid .json file',
                $messageTitle = 'Upload failed',
                $severity = \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR,
                $storeInSession = TRUE
            );
            $this->redirect('main');
        } else {
            $newImagePath = 'uploads';
            $tmpName = $file['name'];
            $tmpFile = $file['tmp_name'];

            $storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
            $storage = $storageRepository->findByUid('1'); //this is the fileadmin storage
            //build the new storage folder
            $targetFolder = $storage->getFolder($newImagePath);

            //file name, be shure that this is unique
            $newFileName = $tmpName;

            //build sys_file
            $storage->addFile($tmpFile, $targetFolder, $newFileName);
            $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();

            $this->addFlashMessage(
                $messageBody = 'Your file was uploaded successfully',
                $messageTitle = 'Upload successful',
                $severity = \TYPO3\CMS\Core\Messaging\FlashMessage::OK,
                $storeInSession = TRUE
            );
            $this->redirect('main');
        }


        /*
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($file["name"]);
        echo $target_file;
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        if ($uploadOk == 0) {
            DebugUtility::debug("Upload is not ok", '');
        } else {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                DebugUtility::debug("File uploaded", '');
                echo "<h1>Upload was successful</h1>";
            } else {
                DebugUtility::debug("Error while uploading", '');
                echo "<h1>Unfortunatly the upload failed</h1>";
            }
        }
        // Open temp file
        $filePath = 'uploads/json/';
        $out = @fopen("{$filePath}.part");
        $in = @fopen("php://input", "rb");

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        // Check if file has been uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            // Strip the temp .part suffix off
            rename($filePath . '.part', $filePath);
            $this->processFile($filePath);
        }

        // save chunked upload dir
        if ($this->chunkedUpload) {
            $this->saveDataInSession($this->uploadPath, 'chunk_path');
        }*/
    }

    /*
    public function saveFormAction(Vendor\MyExtension\Domain\Model\User $formdata) {

        //Store your normal form stuff
        //you don't need to do anything with your image field here!
        //Persist your new model, that we have a valid uid


        //declare the new image path in fileadmin
        //if not exists, it will automaticly added
        $newImagePath = 'JSON';

        //\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($_FILES);

        //image Handling
        if ($_FILES['tx_myExtension_myPlugin']['name']['formimage'][0]) {

            //be careful - you should validate the file type! This is not included here
            $tmpName = $_FILES['tx_myExtension_myPlugin']['name']['formimage'][0];
            $tmpFile  = $_FILES['tx_myExtension_myPlugin']['tmp_name']['formimage'][0];

        $storageRepository = $this->objectManager->get('TYPO3\\CMS\\Core\\Resource\\StorageRepository');
        $storage = $storageRepository->findByUid('1'); //this is the fileadmin storage
        //build the new storage folder
        $targetFolder = $storage->createFolder($newImagePath);

        //file name, be shure that this is unique
        $newFileName = $tmpName;

        //build sys_file
        $movedNewFile = $storage->addFile($originalFilePath, $targetFolder, $newFileName);
        $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();

        //now we build the file reference
        //see private function anotiations!
        $this->buildRelations($myModel->getUid(), $movedNewFile, 'image', 'tx_myextension_domain_model_mymodel', $storagePid);
        }
    }
    */
}