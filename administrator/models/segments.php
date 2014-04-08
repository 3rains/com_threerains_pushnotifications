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
class Threerains_pushnotificationsModelSegments extends JModelList {

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
                'filter_os', 'a.filter_os',
                'filter_devices', 'a.filter_devices',
                'filter_list', 'a.filter_list',
                'filter_language', 'a.filter_language',
                'filter_appunused', 'a.filter_appunused',
                'name', 'a.name',

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

        
		//Filtering filter_os
		$this->setState('filter.filter_os', $app->getUserStateFromRequest($this->context.'.filter.filter_os', 'filter_filter_os', '', 'string'));

		//Filtering filter_devices
		$this->setState('filter.filter_devices', $app->getUserStateFromRequest($this->context.'.filter.filter_devices', 'filter_filter_devices', '', 'string'));

		//Filtering filter_list
		$this->setState('filter.filter_list', $app->getUserStateFromRequest($this->context.'.filter.filter_list', 'filter_filter_list', '', 'string'));

		//Filtering filter_language
		$this->setState('filter.filter_language', $app->getUserStateFromRequest($this->context.'.filter.filter_language', 'filter_filter_language', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_threerains_pushnotifications');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.name', 'asc');
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
        $query->from('`#__threerains_pushnotifications_segments` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		// Join over the foreign key 'filter_os'
		$query->select('#__threerains_pushnotifications_os_1185511.name AS oss_name_1185511');
		$query->join('LEFT', '#__threerains_pushnotifications_os AS #__threerains_pushnotifications_os_1185511 ON #__threerains_pushnotifications_os_1185511.id = a.filter_os');
		// Join over the foreign key 'filter_devices'
		$query->select('#__threerains_pushnotifications_devices_1185512.label AS devices_label_1185512');
		$query->join('LEFT', '#__threerains_pushnotifications_devices AS #__threerains_pushnotifications_devices_1185512 ON #__threerains_pushnotifications_devices_1185512.id = a.filter_devices');
		// Join over the foreign key 'filter_list'
		$query->select('#__threerains_pushnotifications_devices_lists_1185513.list_name AS deviceslists_list_name_1185513');
		$query->join('LEFT', '#__threerains_pushnotifications_devices_lists AS #__threerains_pushnotifications_devices_lists_1185513 ON #__threerains_pushnotifications_devices_lists_1185513.id = a.filter_list');
		// Join over the foreign key 'filter_language'
		$query->select('#__threerains_pushnotifications_languages_1185528.code AS languages_code_1185528');
		$query->join('LEFT', '#__threerains_pushnotifications_languages AS #__threerains_pushnotifications_languages_1185528 ON #__threerains_pushnotifications_languages_1185528.id = a.filter_language');

        

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
                $query->where('( a.name LIKE '.$search.' )');
            }
        }

        

		//Filtering filter_os
		$filter_filter_os = $this->state->get("filter.filter_os");
		if ($filter_filter_os) {
			$query->where("FIND_IN_SET(" . $filter_filter_os. ",a.filter_os)");
		}

		//Filtering filter_devices
		$filter_filter_devices = $this->state->get("filter.filter_devices");
		if ($filter_filter_devices) {
			$query->where("FIND_IN_SET(" . $filter_filter_devices. ",a.filter_devices)");
		}

		//Filtering filter_list
		$filter_filter_list = $this->state->get("filter.filter_list");
		if ($filter_filter_list) {
			$query->where("FIND_IN_SET(" . $filter_filter_list. ",a.filter_list)");
		}

		//Filtering filter_language
		$filter_filter_language = $this->state->get("filter.filter_language");
		if ($filter_filter_language) {
			$query->where("FIND_IN_SET(" . $filter_filter_language. ",a.filter_language)");
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

			if (isset($oneItem->filter_os)) {
				$values = explode(',', $oneItem->filter_os);

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

			$oneItem->filter_os = !empty($textValue) ? implode(', ', $textValue) : $oneItem->filter_os;

			}

			if (isset($oneItem->filter_devices)) {
				$values = explode(',', $oneItem->filter_devices);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('label')
							->from('`#__threerains_pushnotifications_devices`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->label;
					}
				}

			$oneItem->filter_devices = !empty($textValue) ? implode(', ', $textValue) : $oneItem->filter_devices;

			}

			if (isset($oneItem->filter_list)) {
				$values = explode(',', $oneItem->filter_list);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('list_name')
							->from('`#__threerains_pushnotifications_devices_lists`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->list_name;
					}
				}

			$oneItem->filter_list = !empty($textValue) ? implode(', ', $textValue) : $oneItem->filter_list;

			}

			if (isset($oneItem->filter_language)) {
				$values = explode(',', $oneItem->filter_language);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('code')
							->from('`#__threerains_pushnotifications_languages`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->code;
					}
				}

			$oneItem->filter_language = !empty($textValue) ? implode(', ', $textValue) : $oneItem->filter_language;

			}
		}
        return $items;
    }

}
