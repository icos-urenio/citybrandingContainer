<?php

/**
 * @version     1.0.0
 * @package     com_citybranding
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU AFFERO GENERAL PUBLIC LICENSE Version 3; see LICENSE
 * @author      Ioannis Tsampoulatidis <tsampoulatidis@gmail.com> - https://github.com/itsam
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Citybranding.
 */
class CitybrandingViewKeys extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $canManageKeys;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $user = JFactory::getUser();
        $canDo = CitybrandingHelper::getActions();
        $this->canManageKeys = $canDo->get('citybranding.manage.keys');

        $this->state = $this->get('State');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        CitybrandingHelper::addSubmenu('keys');

        $this->addToolbar();
       
        if(!$this->canManageKeys){
            JFactory::getApplication()->enqueueMessage(JText::_('COM_CITYBRANDING_ACTION_NOT_ALLOWED'), 'error');
            return;
        }
        
        $this->sidebar = JHtmlSidebar::render();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        if(!$this->canManageKeys){
            JToolBarHelper::title(JText::_('COM_CITYBRANDING_TITLE_KEYS'), 'key');
            //JToolBarHelper::back();
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Link', 'leftarrow', 'COM_CITYBRANDING_BACK', JRoute::_('index.php?option=com_citybranding', false));
            return;
        }

        require_once JPATH_COMPONENT . '/helpers/citybranding.php';

        $state = $this->get('State');
        $canDo = CitybrandingHelper::getActions($state->get('filter.category_id'));

        JToolBarHelper::title(JText::_('COM_CITYBRANDING_TITLE_KEYS'), 'key');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/key';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
                JToolBarHelper::addNew('key.add', 'JTOOLBAR_NEW');
            }

            if ($canDo->get('core.edit') && isset($this->items[0])) {
                JToolBarHelper::editList('key.edit', 'JTOOLBAR_EDIT');
            }
        }

        if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::custom('keys.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
                JToolBarHelper::custom('keys.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'keys.delete', 'JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
                JToolBarHelper::divider();
                JToolBarHelper::archiveList('keys.archive', 'JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
                JToolBarHelper::custom('keys.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
        }

        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
            if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
                JToolBarHelper::deleteList('', 'keys.delete', 'JTOOLBAR_EMPTY_TRASH');
                JToolBarHelper::divider();
            } else if ($canDo->get('core.edit.state')) {
                JToolBarHelper::trash('keys.trash', 'JTOOLBAR_TRASH');
                JToolBarHelper::divider();
            }
        }

        if ($canDo->get('core.admin')) {
            JToolBarHelper::preferences('com_citybranding');
        }

        //Set sidebar action - New in 3.0
        JHtmlSidebar::setAction('index.php?option=com_citybranding&view=keys');

        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

    }

	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.title' => JText::_('COM_CITYBRANDING_KEYS_TITLE'),
		'a.skey' => JText::_('COM_CITYBRANDING_KEYS_SKEY'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.created_by' => JText::_('COM_CITYBRANDING_KEYS_CREATED_BY'),
		);
	}

}
