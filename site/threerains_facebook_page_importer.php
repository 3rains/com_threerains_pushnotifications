<?php
/**
 * @version     1.0.0
 * @package     com_threerains_facebook_page_importer
 * @copyright   Copyright (C) 2014. Tutti i diritti riservati.
 * @license     GNU General Public License versione 2 o successiva; vedi LICENSE.txt
 * @author      3rains <info@3rains.net> - http://3rains.net
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
$controller	= JControllerLegacy::getInstance('Threerains_facebook_page_importer');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
