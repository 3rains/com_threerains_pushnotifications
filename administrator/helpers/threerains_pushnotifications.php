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

/**
 * Threerains_pushnotifications helper.
 */
class Threerains_pushnotificationsHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_MESSAGESS'),
			'index.php?option=com_threerains_pushnotifications&view=messagess',
			$vName == 'messagess'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_SENT_MESSAGES'),
			'index.php?option=com_threerains_pushnotifications&view=sent_messages',
			$vName == 'sent_messages'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_DEVICES'),
			'index.php?option=com_threerains_pushnotifications&view=devices',
			$vName == 'devices'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_OSS'),
			'index.php?option=com_threerains_pushnotifications&view=oss',
			$vName == 'oss'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_DEVICESLISTS'),
			'index.php?option=com_threerains_pushnotifications&view=deviceslists',
			$vName == 'deviceslists'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_SUBSCRIBERS'),
			'index.php?option=com_threerains_pushnotifications&view=subscribers',
			$vName == 'subscribers'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_DEVICESLOGS'),
			'index.php?option=com_threerains_pushnotifications&view=deviceslogs',
			$vName == 'deviceslogs'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_LANGUAGES'),
			'index.php?option=com_threerains_pushnotifications&view=languages',
			$vName == 'languages'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_SCHEDULEDTASKS'),
			'index.php?option=com_threerains_pushnotifications&view=scheduledtasks',
			$vName == 'scheduledtasks'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_SEGMENTS'),
			'index.php?option=com_threerains_pushnotifications&view=segments',
			$vName == 'segments'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_APPVERSIONS'),
			'index.php?option=com_threerains_pushnotifications&view=appversions',
			$vName == 'appversions'
		);

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_threerains_pushnotifications';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
