<?php
namespace STI\SchemaInjector\Controller;


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
class InjectorController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * action main
     * 
     * @return void
     */
    public function mainAction()
    {
        echo '<h1>Welcome to the schema.org injector!</h1>';
    }

    public function frontendAction() {

        \TYPO3\CMS\Core\Utility\DebugUtility::debug(
            'in InjectorController.php (fe)', 'Debug: ' . __FILE__ . ' in Line: ' . __LINE__
        );
    }

}