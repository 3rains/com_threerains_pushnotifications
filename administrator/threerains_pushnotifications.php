<?php
/**
 * @version     1.0.0
 * @package     com_threerains_pushnotifications
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      3rains <info@3rains.net> - http://3rains.net
 */


// no direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_threerains_pushnotifications')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Threerains_pushnotifications');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
