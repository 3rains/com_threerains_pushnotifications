<?php
/**
 * @version     1.0.0
 * @package     com_threerains_pushnotifications
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      3rains <info@3rains.net> - http://3rains.net
 */

defined('_JEXEC') or die;

require_once(JPATH_COMPONENT."/helpers/devices.php");

abstract class Threerains_pushnotificationsHelper
{	
	public static function storeDevice($device)
	{
	//	mail("andrea.falzetti@3rains.net", "Push", $_SERVER["HTTP_USER_AGENT"]);
		$logDevice = new stdClass();
		$db = JFactory::getDBO();
		$logDevice->language = self::getObjectID('languages', 'code', $device->lang);
		$logDevice->osid = self::getObjectID('os', 'name', $device->os);
		$logDevice->label = $device->device_type." ".$device->device_model;
		$logDevice->app_version = $device->app_version;
		$logDevice->model = $device->device_model;
		$logDevice->token = $device->device_token;
		$logDevice->udid = $device->device_udid;
		$logDevice->state = 1;
		//self::searchAllPossibleGroups($device, $logDevice); // modifica direttamente l'oggetto logDevice per riferimento
		
		$device_id = DevicesHelper::doesDeviceExist($logDevice->udid);
		
		if($device_id)
		{
			// Update
			echo "update";
			$logDevice->id = $device_id;
			DevicesHelper::updateDevice($logDevice);
		}
		else
		{
			// Insert
			echo "insert";
			$device_id = DevicesHelper::insertDevice($logDevice);
		}
		echo "<br/>Device ID = ".$device_id;
		
		
		// oggetto log devce
		return $logDevice;
	}
	
	
	
	/*
	 * Questa funzione si occupa di cercare tutti i possibili gruppi a cui puo' essere iscritto il dispositivo
	 */
	 public static function searchAllPossibleGroups($device, &$logDevice)
	 {
		 // se e' un tablet
		if($device->is_tablet)
		{
			// se e' un android cerco gruppi con le parole 'android' + 'tablet'
			
		}
	 }
	
	/*
	 * Questa funzione costruisce un oggetto 'device'
	 */
	public static function buildDeviceObject($data)
	{
		$deviceLog = new stdClass();
		$deviceLog->timestamp = time();
		$deviceLog->datestamp = date("d-m-Y H:i:s");
		
		$jinput = JFactory::getApplication()->input;
		$useragent = addslashes(htmlspecialchars($_SERVER['HTTP_USER_AGENT']));
		$useragent = $data;
		$deviceLog = (object) array_merge((array) $deviceLog, (array) self::useragent_analyze($useragent));
		$deviceLog->device_token = "9191919919191919919191919";
		$deviceLog->device_udid = "fab31bcac0c42d8e";
		return $deviceLog;
	}
	
	/*
	 * Questa funzione ritorna l'id di una riga del database partendo dal suo valore. Suppongiamo dei parametri:
	 * int getObjectID('languages', 'code', 'en') ritorna: 32 dove 32 e' l'id della lingua English (en)
	 *
	*/
	public static function getObjectID($obejctContext="", $objectKey="", $objectValue="", $searchedKey='id')
	{
		$db = JFactory::getDBO();
		$sql = "SELECT ".$searchedKey." FROM #__threerains_pushnotifications_".$obejctContext." WHERE ".$objectKey." = '".$objectValue."';";
		echo $sql;
		$db->setQuery($sql);
		$db->execute();
		$result = $db->loadResult();
		print_r($result);
		return $result;
	}
	
	public static function useragent_analyze($useragent)
	{
		$analyze = new stdClass();
		$pos = strpos($useragent, "Android");
		
		// ANDROID
		if($pos !== FALSE)
		{
			//com.thepills.app/1.0(Android; 4.4.2; LGE Nexus 4; 768x1184; en
			// Android Device
			$newuseragent = $useragent;
			$analyze->os = 'Android';
			// Cerco il primo flash
			$pos_search = strpos($newuseragent, "/");
			// Cerco il package name
			$analyze->package_name = trim(substr($newuseragent, 0, $pos_search));
			// Taglio
			$newuseragent = substr($newuseragent, $pos_search+1);
			echo $newuseragent;
			// Cerco parentesi (
			$pos_search = strpos($newuseragent, "(");
			$analyze->app_version = trim(substr($newuseragent, 0, $pos_search));
			// Taglio
			$newuseragent = substr($newuseragent, $pos_search+1);
			// Cerco punto e virgola
			$pos_search = strpos($newuseragent, ";");
			$analyze->device_type = trim(substr($newuseragent, 0, $pos_search));
			// Taglio
			$newuseragent = substr($newuseragent, $pos_search+1);
			// Cerco punto e virgola
			$pos_search = strpos($newuseragent, ";");
			$analyze->android_version = trim(substr($newuseragent, 0, $pos_search));
			// Taglio
			$newuseragent = substr($newuseragent, $pos_search+1);
			// Cerco punto e virgola
			$pos_search = strpos($newuseragent, ";");
			$analyze->device_model = trim(substr($newuseragent, 0, $pos_search));
			// Taglio
			$newuseragent = substr($newuseragent, $pos_search+1);
			// Cerco punto e virgola
			$pos_search = strpos($newuseragent, ";");
			$analyze->display = trim(substr($newuseragent, 0, $pos_search));
			// Taglio
			$newuseragent = substr($newuseragent, $pos_search+1);			
			// Cerco punto e virgola
			$pos_search = strpos($newuseragent, ";");
			$analyze->lang = trim(substr($newuseragent, 0, $pos_search));
			// Taglio
			$newuseragent = substr($newuseragent, $pos_search+1);
			// Tablet
			$pos_tablet = strpos($useragent, "Tablet");
			$analyze->is_tablet = ($pos_tablet!==false)?"1":"0";
			
			
			
		}
		else
		{
			//ThePills/1.0 (iPhone; iOS 7.1; Scale/2.00)
			// IOS
			$newuseragent = $useragent;
			$analyze->os = 'iOS';
		}
		
		return $analyze;
	}

}

