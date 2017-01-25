<?php
namespace STI\SchemaInjector\Domain\Model;
use \TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
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
 * InjectorEntity: class not used at the moment
 */

class InjectorEntity extends AbstractEntity
{
    /**
     * @var int page_id
     **/
    protected $inject_page_id = 0;

    /**
     * @var string file
     **/
    protected $inject_file_name = '';

    public function __construct($page_id = 0, $filename = '') {
        $this->setPageId($page_id);
        $this->setFileName($filename);
    }

    public function setPageId($id) {
        $this->inject_page_id = $id;
    }

    public function setFileName($filename) {
        $this->inject_file_name = (string)$filename;
    }


    public function getPageId() {
        return $this->inject_page_id;
    }

    public function getFileName() {
        return $this->inject_file_name;
    }
    
}