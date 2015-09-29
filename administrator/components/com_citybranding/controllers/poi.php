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
jimport('joomla.application.component.controllerform');
require_once JPATH_COMPONENT_SITE . '/helpers/citybranding.php';
JPluginHelper::importPlugin('citybranding');
/**
 * Poi controller class.
 */
class CitybrandingControllerPoi extends JControllerForm
{

    function __construct() {
        $this->view_list = 'pois';
        parent::__construct();
    }
    
    //override postSaveHook
    protected function postSaveHook(JModelLegacy $model, $validData = array())
    {
        //A: inform log table about the new poi
        if($validData['id'] == 0){
            
            $log = JTable::getInstance('Log', 'CitybrandingTable', array());

            $catTitle = CitybrandingFrontendHelper::getCategoryNameByCategoryId($validData['catid']);

            $data2['id'] = 0;
            $data2['state'] = 1;
            $data2['action'] = JText::_('COM_CITYBRANDING_LOGS_ACTION_INITIAL_COMMIT');
            $data2['poiid'] = $model->getItem()->get('id');
            $data2['stepid'] = $validData['stepid'];
            $data2['description'] = JText::_('COM_CITYBRANDING_LOGS_ACTION_INITIAL_COMMIT') . ' ' . JText::_('COM_CITYBRANDING_LOGS_AT_CATEGORY') . ' ' . $catTitle;
            $data2['created'] = $validData['created'];
            $data2['created_by'] = $validData['created_by'];
            $data2['updated'] = $validData['created'];
            $data2['language'] = $validData['language'];
            $data2['rules'] = $validData['rules'];
            

            if (!$log->bind($data2))
            {
                JFactory::getApplication()->enqueueMessage('Cannot bind data to log table', 'error'); 
            }

            if (!$log->save($data2))
            {
                JFactory::getApplication()->enqueueMessage('Cannot save data to log table', 'error'); 
            }


            try
            {
                $dispatcher = JEventDispatcher::getInstance();
                $results = $dispatcher->trigger( 'onAfterNewPoiAdded', array( $model, $validData ) );
                // Check the returned results. This is for plugins that don't throw
                // exceptions when they encounter serious errors.
                if (in_array(false, $results))
                {
                    throw new Exception($dispatcher->getError(), 500);
                }
            }
            catch (Exception $e)
            {
                // Handle a caught exception.
                throw $e;
            }


        }
        else {

            //a. check for step modification
            if(isset($validData['is_step_modified']) && $validData['is_step_modified'] === 'true'){
                $user = JFactory::getUser();
                $log = JTable::getInstance('Log', 'CitybrandingTable', array());

                $data2['id'] = 0;
                $data2['state'] = 1;
                $data2['action'] = JText::_('COM_CITYBRANDING_LOGS_ACTION_STEP_MODIFIED');
                $data2['poiid'] = $validData['id'];
                $data2['stepid'] = $validData['stepid'];
                $data2['description'] = $validData['step_modified_description'];
                $data2['created'] = $validData['updated'];
                $data2['created_by'] = $user->id;
                $data2['updated'] = $validData['updated'];
                $data2['language'] = $validData['language'];
                $data2['rules'] = $validData['rules'];

                if (!$log->bind($data2))
                {
                    JFactory::getApplication()->enqueueMessage('Cannot bind data to log table', 'error'); 
                }

                if (!$log->save($data2))
                {
                    JFactory::getApplication()->enqueueMessage('Cannot save data to log table', 'error'); 
                }
                
                $dispatcher = JEventDispatcher::getInstance();
                $dispatcher->trigger( 'onAfterStepModified', array( $model, $validData ) );
            }

            //b. check for category modification
            if(isset($validData['is_category_modified']) && $validData['is_category_modified'] === 'true'){
                $user = JFactory::getUser();
                $log = JTable::getInstance('Log', 'CitybrandingTable', array());

                $data2['id'] = 0;
                $data2['state'] = 1;
                $data2['action'] = JText::_('COM_CITYBRANDING_LOGS_ACTION_CATEGORY_MODIFIED');
                $data2['poiid'] = $validData['id'];
                $data2['stepid'] = $validData['stepid'];
                $data2['description'] = $validData['category_modified_description'];
                $data2['created'] = $validData['updated'];
                $data2['created_by'] = $user->id;
                $data2['updated'] = $validData['updated'];
                $data2['language'] = $validData['language'];
                $data2['rules'] = $validData['rules'];

                if (!$log->bind($data2))
                {
                    JFactory::getApplication()->enqueueMessage('Cannot bind data to log table', 'error'); 
                }

                if (!$log->save($data2))
                {
                    JFactory::getApplication()->enqueueMessage('Cannot save data to log table', 'error'); 
                }

                $dispatcher = JEventDispatcher::getInstance();
                $dispatcher->trigger( 'onAfterCategoryModified', array( $model, $validData ) );                
            }

        }   

        //B: move any images only if record is new
    	if($validData['id'] == 0) {
        	//check if any files uploaded
        	$obj = json_decode( $validData['photo'] );
    		if(empty($obj->files))
    			return;

            $srcDir = JPATH_ROOT . '/' . $obj->imagedir . '/' . $obj->id;
            $dstDir = JPATH_ROOT . '/' . $obj->imagedir . '/' . $model->getItem()->get('id');

    		$success = rename ( $srcDir , $dstDir );

            if($success){
                //update photo json isnew, id
                unset($obj->isnew);
                $obj->id = $model->getItem()->get('id');
                $photo = json_encode($obj);

                // Create an object for the record we are going to update.
                $object = new stdClass();
                $object->id = $model->getItem()->get('id');
                $object->photo = $photo;
                // Update photo
                $result = JFactory::getDbo()->updateObject('#__citybranding_pois', $object, 'id');

            }
            else {
                JFactory::getApplication()->enqueueMessage('Cannot move '.$srcDir.' to '.$dstDir.'. Check folder rights', 'error'); 
            }

        }
    }

    /*
    public function printPoi($pk = null)
    {
        // Get the input
        $input = JFactory::getApplication()->input;
        $poiid = $input->get('id', 0);
        $model = $this->getModel();
        $model->setState('printid', $poiid);

        $v = $this->getView('poi', 'print');              //view.print.php
        $v->setModel($model, true);                         //load poi model
        $v->setModel($this->getModel('Logs', 'CitybrandingModel'));  //load logs as well
        $v->display('print');                               //default_print   

        // Redirect to the list screen.
        //$this->setRedirect(JRoute::_('index.php?option=com_citybranding&view=poi&layout=edit&id='.$poiid, false));
    }
    */

}