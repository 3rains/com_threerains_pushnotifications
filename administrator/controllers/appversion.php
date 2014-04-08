<?php
/**
 * @version     1.0.0
 * @package     com_threerains_pushnotifications
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      3rains <info@3rains.net> - http://3rains.net
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Appversion controller class.
 */
class Threerains_pushnotificationsControllerAppversion extends JControllerForm
{

    function __construct() {
        $this->view_list = 'appversions';
        parent::__construct();
    }

}