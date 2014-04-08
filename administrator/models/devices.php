<?php

/**
 * @version     1.0.0
 * @package     com_threerains_pushnotifications
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      3rains <info@3rains.net> - http://3rains.net
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Threerains_pushnotifications records.
 */
class Threerains_pushnotificationsModelDevices extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'token', 'a.token',
                'osid', 'a.osid',
                'label', 'a.label',
                'language', 'a.language',
                'app_version', 'a.app_version',
                'model', 'a.model',
                'udid', 'a.udid',

            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        
		//Filtering osid
		$this->setState('filter.osid', $app->getUserStateFromRequest($this->context.'.filter.osid', 'filter_osid', '', 'string'));

		//Filtering language
		$this->setState('filter.language', $app->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '', 'string'));

		//Filtering app_version
		$this->setState('filter.app_version', $app->getUserStateFromRequest($this->context.'.filter.app_version', 'filter_app_version', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_threerains_pushnotifications');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.checked_out', 'asc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        $query->from('`#__threerains_pushnotifications_devices` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		// Join over the foreign key 'osid'
		$query->select('#__threerains_pushnotifications_os_1183681.name AS oss_name_1183681');
		$query->join('LEFT', '#__threerains_pushnotifications_os AS #__threerains_pushnotifications_os_1183681 ON #__threerains_pushnotifications_os_1183681.id = a.osid');
		// Join over the foreign key 'language'
		$query->select('#__threerains_pushnotifications_languages_1185084.name AS languages_name_1185084');
		$query->join('LEFT', '#__threerains_pushnotifications_languages AS #__threerains_pushnotifications_languages_1185084 ON #__threerains_pushnotifications_languages_1185084.id = a.language');
		// Join over the foreign key 'app_version'
		$query->select('#__threerains_pushnotifications_app_versions_1185102.version_code AS appversions_version_code_1185102');
		$query->join('LEFT', '#__threerains_pushnotifications_app_versions AS #__threerains_pushnotifications_app_versions_1185102 ON #__threerains_pushnotifications_app_versions_1185102.id = a.app_version');

        

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.checked_out LIKE '.$search.'  OR  a.osid LIKE '.$search.'  OR  a.label LIKE '.$search.'  OR  a.language LIKE '.$search.'  OR  a.app_version LIKE '.$search.'  OR  a.model LIKE '.$search.'  OR  a.udid LIKE '.$search.' )');
            }
        }

        

		//Filtering osid
		$filter_osid = $this->state->get("filter.osid");
		if ($filter_osid) {
			$query->where("a.osid = '".$db->escape($filter_osid)."'");
		}

		//Filtering language
		$filter_language = $this->state->get("filter.language");
		if ($filter_language) {
			$query->where("a.language = '".$db->escape($filter_language)."'");
		}

		//Filtering app_version
		$filter_app_version = $this->state->get("filter.app_version");
		if ($filter_app_version) {
			$query->where("a.app_version = '".$db->escape($filter_app_version)."'");
		}


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
		foreach ($items as $oneItem) {

			if (isset($oneItem->osid)) {
				$values = explode(',', $oneItem->osid);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('name')
							->from('`#__threerains_pushnotifications_os`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->name;
					}
				}

			$oneItem->osid = !empty($textValue) ? implode(', ', $textValue) : $oneItem->osid;

			}

			if (isset($oneItem->language)) {
				$values = explode(',', $oneItem->language);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('name')
							->from('`#__threerains_pushnotifications_languages`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->name;
					}
				}

			$oneItem->language = !empty($textValue) ? implode(', ', $textValue) : $oneItem->language;

			}

			if (isset($oneItem->app_version)) {
				$values = explode(',', $oneItem->app_version);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('version_code')
							->from('`#__threerains_pushnotifications_app_versions`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->version_code;
					}
				}

			$oneItem->app_version = !empty($textValue) ? implode(', ', $textValue) : $oneItem->app_version;

			}
		}
        return $items;
    }

}
