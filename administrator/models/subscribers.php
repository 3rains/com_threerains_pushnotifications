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
class Threerains_pushnotificationsModelSubscribers extends JModelList {

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
                'device_id', 'a.device_id',
                'list_id', 'a.list_id',

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

        
		//Filtering device_id
		$this->setState('filter.device_id', $app->getUserStateFromRequest($this->context.'.filter.device_id', 'filter_device_id', '', 'string'));

		//Filtering list_id
		$this->setState('filter.list_id', $app->getUserStateFromRequest($this->context.'.filter.list_id', 'filter_list_id', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_threerains_pushnotifications');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.device_id', 'asc');
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
        $query->from('`#__threerains_pushnotifications_subscriptions` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		// Join over the foreign key 'device_id'
		$query->select('#__threerains_pushnotifications_devices_1183717.id AS devices_id_1183717');
		$query->join('LEFT', '#__threerains_pushnotifications_devices AS #__threerains_pushnotifications_devices_1183717 ON #__threerains_pushnotifications_devices_1183717.id = a.device_id');
		// Join over the foreign key 'list_id'
		$query->select('#__threerains_pushnotifications_devices_lists_1183718.list_name AS deviceslists_list_name_1183718');
		$query->join('LEFT', '#__threerains_pushnotifications_devices_lists AS #__threerains_pushnotifications_devices_lists_1183718 ON #__threerains_pushnotifications_devices_lists_1183718.id = a.list_id');

        

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
                $query->where('( a.device_id LIKE '.$search.'  OR  a.list_id LIKE '.$search.' )');
            }
        }

        

		//Filtering device_id
		$filter_device_id = $this->state->get("filter.device_id");
		if ($filter_device_id) {
			$query->where("a.device_id = '".$db->escape($filter_device_id)."'");
		}

		//Filtering list_id
		$filter_list_id = $this->state->get("filter.list_id");
		if ($filter_list_id) {
			$query->where("a.list_id = '".$db->escape($filter_list_id)."'");
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

			if (isset($oneItem->device_id)) {
				$values = explode(',', $oneItem->device_id);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('id')
							->from('`#__threerains_pushnotifications_devices`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->id;
					}
				}

			$oneItem->device_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->device_id;

			}

			if (isset($oneItem->list_id)) {
				$values = explode(',', $oneItem->list_id);

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

			$oneItem->list_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->list_id;

			}
		}
        return $items;
    }

}
