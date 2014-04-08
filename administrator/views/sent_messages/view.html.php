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
class Threerains_pushnotificationsViewSent_messages extends JViewLegacy
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
        
		Threerains_pushnotificationsHelper::addSubmenu('sent_messages');
        
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

		JToolBarHelper::title(JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_TITLE_SENT_MESSAGES'), 'sent_messages.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('sent_messages.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('sent_messages.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'sent_messages.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('sent_messages.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('sent_messages.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'sent_messages.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('sent_messages.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_threerains_pushnotifications');
		}
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_threerains_pushnotifications&view=sent_messages');
        
        $this->extra_sidebar = '';
        
			//Filter for the field date_of_sent
			$this->extra_sidebar .= '<small><label for="filter_from_date_of_sent">From Date of sent</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.date_of_sent.from'), 'filter_from_date_of_sent', 'filter_from_date_of_sent', '%Y-%m-%d', 'style="width:142px;" onchange="this.form.submit();"');
			$this->extra_sidebar .= '<small><label for="filter_to_date_of_sent">To Date of sent</label></small>';
			$this->extra_sidebar .= JHtml::_('calendar', $this->state->get('filter.date_of_sent.to'), 'filter_to_date_of_sent', 'filter_to_date_of_sent', '%Y-%m-%d', 'style="width:142px;" onchange="this.form.submit();"');
			$this->extra_sidebar .= '<hr class="hr-condensed">';

        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.date_of_sent' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SENT_MESSAGES_DATE_OF_SENT'),
		'a.title' => JText::_('COM_THREERAINS_PUSHNOTIFICATIONS_SENT_MESSAGES_TITLE'),
		);
	}

    
}
