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
class Threerains_pushnotificationsModelSent_messages extends JModelList {

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
                'date_of_sent', 'a.date_of_sent',
                'text', 'a.text',
                'title', 'a.title',

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

        
		//Filtering date_of_sent
		$this->setState('filter.date_of_sent.from', $app->getUserStateFromRequest($this->context.'.filter.date_of_sent.from', 'filter_from_date_of_sent', '', 'string'));
		$this->setState('filter.date_of_sent.to', $app->getUserStateFromRequest($this->context.'.filter.date_of_sent.to', 'filter_to_date_of_sent', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_threerains_pushnotifications');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.date_of_sent', 'asc');
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
        $query->from('`#__threerains_pushnotifications_sent_messages` AS a');

        

        

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.date_of_sent LIKE '.$search.'  OR  a.title LIKE '.$search.' )');
            }
        }

        

		//Filtering date_of_sent
		$filter_date_of_sent_from = $this->state->get("filter.date_of_sent.from");
		if ($filter_date_of_sent_from) {
			$query->where("a.date_of_sent >= '".$db->escape($filter_date_of_sent_from)."'");
		}
		$filter_date_of_sent_to = $this->state->get("filter.date_of_sent.to");
		if ($filter_date_of_sent_to) {
			$query->where("a.date_of_sent <= '".$db->escape($filter_date_of_sent_to)."'");
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
        
        return $items;
    }

}
