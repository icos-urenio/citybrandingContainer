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
 * View to print
  This class is (currently) not used
  since printing is decided to take place directly in the edit tmpl.
  To be used in the future to support exporting in multiple formats...
 */
class CitybrandingViewPoi extends JViewLegacy {

    protected $state;
    protected $item;
    protected $logs;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        
        $this->state = $this->get('State');
        print_r($this->state);
        $this->item = $this->getModel('Poi')->getItem($this->state->printid);
        if($this->item->id > 0)
            $this->logs = $this->getModel('Logs')->getItemsByPoi($this->item->id);
        else
            $this->logs = array();

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
        JToolBarHelper::title(JText::_('COM_CITYBRANDING_PRINT').' '.$this->state->printid, 'print.png');
        //on existing allow printing
        $url = JRoute::_('index.php?option=com_citybranding&view=poi&layout=edit&id='.$this->state->printid, false);
        //$toolbar->appendButton('Back', 'back.png', 'JTOOLBAR_BACK', $url);
     
        JToolBarHelper::back('Back2', $url);
        // Add a back button.
        JToolBarHelper::back('Print', 'print.png', 'print.png', 'javascript:window.print()');

        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton('Link', 'back', 'Go back', $url);
        $layout = new JLayoutFile('joomla.toolbar.popup');
        $dhtml = $layout->render(
            array(
                'doTask' => 'javascript:window.print()',
                'class' => 'icon-print',
                'text' => JText::_('COM_CITYBRANDING_PRINT'),
                'name' => 'collapseModal'
        ));

        $bar->appendButton('Custom', $dhtml, 'print');
    }

}
