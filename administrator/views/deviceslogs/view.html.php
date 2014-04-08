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
class Threerains_pushnotificationsViewDeviceslogs extends JViewLegacy
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
        
		Threerains_pushnotificationsHelper::addSubmenu('deviceslogs');
        
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

		JToolBarHelper::title(JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_DEVICESLOGS'), 'deviceslogs.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/deviceslog';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('deviceslog.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('deviceslog.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('deviceslogs.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('deviceslogs.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'deviceslogs.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('deviceslogs.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('deviceslogs.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'deviceslogs.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('deviceslogs.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_threerains_pushnotifications');
		}
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_threerains_pushnotifications&view=deviceslogs');
        
        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);
        //Filter for the field ".device_id;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_threerains_pushnotifications.deviceslog', 'deviceslog');

        $field = $form->getField('device_id');

        $query = $form->getFieldAttribute('filter_device_id','query');
        $translate = $form->getFieldAttribute('filter_device_id','translate');
        $key = $form->getFieldAttribute('filter_device_id','key_field');
        $value = $form->getFieldAttribute('filter_device_id','value_field');

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
            'Device',
            'filter_device_id',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.device_id')),
            true
        );
			//Filter for the field timestamp
			$this->extra_sidebar .= '<small><label for="filter_from_timestamp">From Timestamp</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.timestamp.from'), 'filter_from_timestamp', 'filter_from_timestamp', '%Y-%m-%d', 'style="width:142px;" onchange="this.form.submit();"');
			$this->extra_sidebar .= '<small><label for="filter_to_timestamp">To Timestamp</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.timestamp.to'), 'filter_to_timestamp', 'filter_to_timestamp', '%Y-%m-%d', 'style="width:142px;" onchange="this.form.submit();"');
			$this->extra_sidebar .= '<hr class="hr-condensed">';

        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.checked_out' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_DEVICESLOGS_CHECKED_OUT'),
		'a.checked_out_time' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_DEVICESLOGS_CHECKED_OUT_TIME'),
		'a.device_id' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_DEVICESLOGS_DEVICE_ID'),
		'a.timestamp' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_DEVICESLOGS_TIMESTAMP'),
		);
	}

    
}
