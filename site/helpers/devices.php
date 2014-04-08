<?php
/**
 * @version     1.0.0
 * @package     com_threerains_pushnotifications
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      3rains <info@3rains.net> - http://3rains.net
 */

defined('_JEXEC') or die;

abstract class DevicesHelper
{	
	/*
	 * This functions checks if the device is already in the database
	 */
	public static function doesDeviceExist($device_udid="")
	{
		$db = JFactory::getDBO();
		$sql = "SELECT id FROM #__threerains_pushnotifications_devices WHERE udid = '".$device_udid."';";
		$db->setQuery($sql);
		$db->execute();
		return $db->loadResult();
	}
	
	/*
	 * This functions inserts the device in the database
	 */	
	public static function insertDevice($device)
	{
		$result = JFactory::getDbo()->insertObject('#__threerains_pushnotifications_devices', $device);
		return $result;
	}	
	
	/*
	 * This functions updates the device in the database
	 */	
	public static function updateDevice($device)
	{
		$result = JFactory::getDbo()->updateObject('#__threerains_pushnotifications_devices', $device, 'id');
		return $result;
	}
}

