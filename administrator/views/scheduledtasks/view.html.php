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
class Threerains_pushnotificationsViewScheduledtasks extends JViewLegacy
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
        
		Threerains_pushnotificationsHelper::addSubmenu('scheduledtasks');
        
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

		JToolBarHelper::title(JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_SCHEDULEDTASKS'), 'scheduledtasks.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/scheduledtask';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('scheduledtask.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('scheduledtask.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('scheduledtasks.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('scheduledtasks.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'scheduledtasks.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('scheduledtasks.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('scheduledtasks.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'scheduledtasks.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('scheduledtasks.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_threerains_pushnotifications');
		}
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_threerains_pushnotifications&view=scheduledtasks');
        
        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

			//Filter for the field send_date
			$this->extra_sidebar .= '<small><label for="filter_from_send_date">From Send Date</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.send_date.from'), 'filter_from_send_date', 'filter_from_send_date', '%Y-%m-%d', 'style="width:142px;" onchange="this.form.submit();"');
			$this->extra_sidebar .= '<small><label for="filter_to_send_date">To Send Date</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.send_date.to'), 'filter_to_send_date', 'filter_to_send_date', '%Y-%m-%d', 'style="width:142px;" onchange="this.form.submit();"');
			$this->extra_sidebar .= '<hr class="hr-condensed">';
        //Filter for the field ".segmentid;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_threerains_pushnotifications.scheduledtask', 'scheduledtask');

        $field = $form->getField('segmentid');

        $query = $form->getFieldAttribute('filter_segmentid','query');
        $translate = $form->getFieldAttribute('filter_segmentid','translate');
        $key = $form->getFieldAttribute('filter_segmentid','key_field');
        $value = $form->getFieldAttribute('filter_segmentid','value_field');

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
            'Segment',
            'filter_segmentid',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.segmentid')),
            true
        );
        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.checked_out' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SCHEDULEDTASKS_CHECKED_OUT'),
		'a.checked_out_time' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SCHEDULEDTASKS_CHECKED_OUT_TIME'),
		'a.created_by' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SCHEDULEDTASKS_CREATED_BY'),
		'a.send_date' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SCHEDULEDTASKS_SEND_DATE'),
		'a.send_hour' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SCHEDULEDTASKS_SEND_HOUR'),
		'a.send_minute' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SCHEDULEDTASKS_SEND_MINUTE'),
		'a.segmentid' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SCHEDULEDTASKS_SEGMENTID'),
		);
	}

    
}
