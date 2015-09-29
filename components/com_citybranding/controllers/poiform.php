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
require_once JPATH_COMPONENT . '/controller.php';
require_once JPATH_COMPONENT . '/helpers/citybranding.php';
JPluginHelper::importPlugin('citybranding');

/**
 * Poi controller class.
 */
class CitybrandingControllerPoiForm extends CitybrandingController {

    /**
     * Method to check out an item for editing and redirect to the edit form.
     *
     * @since	1.6
     */
    public function edit() {
        $app = JFactory::getApplication();

        // Get the previous edit id (if any) and the current edit id.
        $previousId = (int) $app->getUserState('com_citybranding.edit.poi.id');
        $editId = JFactory::getApplication()->input->getInt('id', null, 'array');

        // Set the user id for the user to edit in the session.
        $app->setUserState('com_citybranding.edit.poi.id', $editId);

        // Get the model.
        $model = $this->getModel('PoiForm', 'CitybrandingModel');

        // Check out the item
        if ($editId) {
            $model->checkout($editId);
        }

        // Check in the previous user.
        if ($previousId) {
            $model->checkin($previousId);
        }

        // Redirect to the edit screen.
        $this->setRedirect(JRoute::_('index.php?option=com_citybranding&id=&view=poi&layout=edit', false));
    }

    /**
     * Method to save a user's profile data.
     *
     * @return	void
     * @since	1.6
     */
    public function save() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('PoiForm', 'CitybrandingModel');

        // Get the user data.
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            $input = $app->input;
            $jform = $input->get('jform', array(), 'ARRAY');

            // Save the data in the session.
            $app->setUserState('com_citybranding.edit.poi.data', $jform, array());

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_citybranding.edit.poi.id');
            $this->setRedirect(JRoute::_('index.php?option=com_citybranding&view=poiform&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->save($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_citybranding.edit.poi.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_citybranding.edit.poi.id');
            $this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_citybranding&view=poiform&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_citybranding.edit.poi.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_CITYBRANDING_ITEM_SAVED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_citybranding&view=pois' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_citybranding.edit.poi.data', null);

        //emulate postSaveHook like extending from JControllerForm
        $this->postSaveHook($model, $data);
    }

    function cancel() {
        
        $app = JFactory::getApplication();

        // Get the current edit id.
        $editId = (int) $app->getUserState('com_citybranding.edit.poi.id');

        // Get the model.
        $model = $this->getModel('PoiForm', 'CitybrandingModel');

        // Check in the item
        if ($editId) {
            $model->checkin($editId);
        }
        
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_citybranding&view=pois' : $item->link);
        $this->setRedirect(JRoute::_($url, false));
    }

    public function remove() {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app = JFactory::getApplication();
        $model = $this->getModel('PoiForm', 'CitybrandingModel');

        // Get the user data.
        $data = JFactory::getApplication()->input->get('jform', array(), 'array');

        // Validate the posted data.
        $form = $model->getForm();
        if (!$form) {
            JError::raiseError(500, $model->getError());
            return false;
        }

        // Validate the posted data.
        $data = $model->validate($form, $data);

        // Check for errors.
        if ($data === false) {
            // Get the validation messages.
            $errors = $model->getErrors();

            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
                if ($errors[$i] instanceof Exception) {
                    $app->enqueueMessage($errors[$i]->getMessage(), 'warning');
                } else {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }

            // Save the data in the session.
            $app->setUserState('com_citybranding.edit.poi.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_citybranding.edit.poi.id');
            $this->setRedirect(JRoute::_('index.php?option=com_citybranding&view=poi&layout=edit&id=' . $id, false));
            return false;
        }

        // Attempt to save the data.
        $return = $model->delete($data);

        // Check for errors.
        if ($return === false) {
            // Save the data in the session.
            $app->setUserState('com_citybranding.edit.poi.data', $data);

            // Redirect back to the edit screen.
            $id = (int) $app->getUserState('com_citybranding.edit.poi.id');
            $this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_citybranding&view=poi&layout=edit&id=' . $id, false));
            return false;
        }


        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }

        // Clear the profile id from the session.
        $app->setUserState('com_citybranding.edit.poi.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_CITYBRANDING_ITEM_DELETED_SUCCESSFULLY'));
        $menu = JFactory::getApplication()->getMenu();
        $item = $menu->getActive();
        $url = (empty($item->link) ? 'index.php?option=com_citybranding&view=pois' : $item->link);
        $this->setRedirect(JRoute::_($url, false));

        // Flush the data from the session.
        $app->setUserState('com_citybranding.edit.poi.data', null);
    }

    //simulate postSaveHook to move any images to the correct directory
    public function postSaveHook (JModelLegacy $model, $validData = array())
    {
        
        $insertid = JFactory::getApplication()->getUserState('com_citybranding.edit.poi.insertid');

        //A: inform log table about the new poi
        if($validData['id'] == 0){

            JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');
            $log = JTable::getInstance('Log', 'CitybrandingTable', array());

            $catTitle = CitybrandingFrontendHelper::getCategoryNameByCategoryId($validData['catid']);

            $data2['id'] = 0;
            $data2['state'] = 1;
            $data2['action'] = JText::_('COM_CITYBRANDING_LOGS_ACTION_INITIAL_COMMIT');
            $data2['poiid'] = $insertid; //$model->getItem()->get('id');
            $data2['stepid'] = $validData['stepid'];
            $data2['description'] = JText::_('COM_CITYBRANDING_LOGS_ACTION_INITIAL_COMMIT') . ' ' . JText::_('COM_CITYBRANDING_LOGS_AT_CATEGORY') . ' ' . $catTitle;
            $data2['created'] = $validData['created'];
            $data2['created_by'] = $validData['created_by'];
            $data2['updated'] = $validData['created'];
            $data2['language'] = $validData['language'];
            if(isset($data2['rules']))
            {
                $data2['rules'] = $validData['rules'];
            }
            
            if (!$log->bind($data2))
            {
                JFactory::getApplication()->enqueueMessage('Cannot bind data to log table', 'error'); 
            }

            if (!$log->save($data2))
            {
                JFactory::getApplication()->enqueueMessage('Cannot save data to log table', 'error'); 
            }

            $dispatcher = JEventDispatcher::getInstance();
            $results = $dispatcher->trigger( 'onAfterNewPoiAdded', array( $model, $validData, $insertid ) );            
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
                if(isset($data2['rules']))
                {
                    $data2['rules'] = $validData['rules'];
                }

                if (!$log->bind($data2))
                {
                    JFactory::getApplication()->enqueueMessage('Cannot bind data to log table', 'error'); 
                }

                if (!$log->save($data2))
                {
                    JFactory::getApplication()->enqueueMessage('Cannot save data to log table', 'error'); 
                }

                $dispatcher = JEventDispatcher::getInstance();
                $dispatcher->trigger( 'onAfterStepModified', array( $model, $validData, $insertid ) );
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
                $dispatcher->trigger( 'onAfterCategoryModified', array( $model, $validData, $insertid ) ); 
            }


        }    


        //B: move any images only if record is new
        if($validData['id'] == 0){
            //check if any files uploaded
            $obj = json_decode( $validData['photo'] );
            if(empty($obj->files))
                return;

            $srcDir = JPATH_ROOT . '/' . $obj->imagedir . '/' . $obj->id;
            $dstDir = JPATH_ROOT . '/' . $obj->imagedir . '/' . $insertid;
            $success = rename ( $srcDir , $dstDir );

            if($success){
                //update photo json isnew, id
                unset($obj->isnew);
                $obj->id = $insertid;
                $photo = json_encode($obj);

                // Create an object for the record we are going to update.
                $object = new stdClass();
                $object->id = $insertid;
                $object->photo = $photo;
                // Update photo
                $result = JFactory::getDbo()->updateObject('#__citybranding_pois', $object, 'id');

            }
            else {
                JFactory::getApplication()->enqueueMessage('Cannot move '.$srcDir.' to '.$dstDir.'. Check folder rights', 'error'); 
            }

        }


    }
}
