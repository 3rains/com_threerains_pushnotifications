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

jimport('joomla.application.component.view');

/**
 * View class for a list of Threerains_pushnotifications.
 */
class Threerains_pushnotificationsViewSegments extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		Threerains_pushnotificationsHelper::addSubmenu('segments');
        
		$this->addToolbar();
        
        $this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/threerains_pushnotifications.php';

		$state	= $this->get('State');
		$canDo	= Threerains_pushnotificationsHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_SEGMENTS'), 'segments.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/segment';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('segment.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('segment.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('segments.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('segments.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'segments.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('segments.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('segments.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'segments.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('segments.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_threerains_pushnotifications');
		}
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_threerains_pushnotifications&view=segments');
        
        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);
        //Filter for the field ".filter_os;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_threerains_pushnotifications.segment', 'segment');

        $field = $form->getField('filter_os');

        $query = $form->getFieldAttribute('filter_filter_os','query');
        $translate = $form->getFieldAttribute('filter_filter_os','translate');
        $key = $form->getFieldAttribute('filter_filter_os','key_field');
        $value = $form->getFieldAttribute('filter_filter_os','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            'Filter Os',
            'filter_filter_os',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.filter_os')),
            true
        );        //Filter for the field ".filter_devices;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_threerains_pushnotifications.segment', 'segment');

        $field = $form->getField('filter_devices');

        $query = $form->getFieldAttribute('filter_filter_devices','query');
        $translate = $form->getFieldAttribute('filter_filter_devices','translate');
        $key = $form->getFieldAttribute('filter_filter_devices','key_field');
        $value = $form->getFieldAttribute('filter_filter_devices','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            'Filter Devices',
            'filter_filter_devices',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.filter_devices')),
            true
        );        //Filter for the field ".filter_list;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_threerains_pushnotifications.segment', 'segment');

        $field = $form->getField('filter_list');

        $query = $form->getFieldAttribute('filter_filter_list','query');
        $translate = $form->getFieldAttribute('filter_filter_list','translate');
        $key = $form->getFieldAttribute('filter_filter_list','key_field');
        $value = $form->getFieldAttribute('filter_filter_list','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            'Filter List',
            'filter_filter_list',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.filter_list')),
            true
        );        //Filter for the field ".filter_language;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_threerains_pushnotifications.segment', 'segment');

        $field = $form->getField('filter_language');

        $query = $form->getFieldAttribute('filter_filter_language','query');
        $translate = $form->getFieldAttribute('filter_filter_language','translate');
        $key = $form->getFieldAttribute('filter_filter_language','key_field');
        $value = $form->getFieldAttribute('filter_filter_language','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            'Filter Language',
            'filter_filter_language',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.filter_language')),
            true
        );
        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.checked_out' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SEGMENTS_CHECKED_OUT'),
		'a.checked_out_time' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SEGMENTS_CHECKED_OUT_TIME'),
		'a.filter_os' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SEGMENTS_FILTER_OS'),
		'a.filter_devices' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SEGMENTS_FILTER_DEVICES'),
		'a.filter_list' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SEGMENTS_FILTER_LIST'),
		'a.filter_language' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SEGMENTS_FILTER_LANGUAGE'),
		'a.filter_appunused' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SEGMENTS_FILTER_APPUNUSED'),
		'a.name' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SEGMENTS_NAME'),
		);
	}

    
}
