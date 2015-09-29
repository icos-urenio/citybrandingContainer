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
 * View to edit
 */
class CitybrandingViewLog extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors));
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar() {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $canDo = CitybrandingHelper::getActions();
        $user = JFactory::getUser();
        $canManageLogs = $canDo->get('citybranding.manage.logs');
        $isNew = ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
            $checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }

        JToolBarHelper::title(JText::_('COM_CITYBRANDING_TITLE_LOG'), 'stack');
        if($canManageLogs){
            // If not checked out, can save the item.
            if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create')))) {

                JToolBarHelper::apply('log.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('log.save', 'JTOOLBAR_SAVE');
            }
            if (!$checkedOut && ($canDo->get('core.create'))) {
                //JToolBarHelper::custom('log.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            }
            // If an existing item, can save to a copy.
            if (!$isNew && $canDo->get('core.create')) {
                //JToolBarHelper::custom('log.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
            }
        }    

        if (empty($this->item->id)) {
            JToolBarHelper::cancel('log.cancel', 'JTOOLBAR_CANCEL');
        } else {
            JToolBarHelper::cancel('log.cancel', 'JTOOLBAR_CLOSE');
        }
    }

}
