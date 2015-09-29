<?php
/**
 * @version     1.0.0
 * @package     com_citybranding
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU AFFERO GENERAL PUBLIC LICENSE Version 3; see LICENSE
 * @author      Ioannis Tsampoulatidis <tsampoulatidis@gmail.com> - https://github.com/itsam
 */
error_reporting(E_ALL | E_STRICT);
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Poi controller class.
 */
class CitybrandingControllerUsers extends JControllerLegacy
{

	public function members()
	{
        // Check for request forgeries.
		if(!JSession::checkToken('get')){
			echo new JResponseJson(null, 'Invalid Token', true);
			jexit();
		}

		$app = JFactory::getApplication();
		$jinput = $app->input;
		$groups = $jinput->get('groups', null);
		if($groups == null){
			echo new JResponseJson(null, 'No given group', true);
			JFactory::getApplication()->close();
		}

		try {
			//TODO: move this on CitybrandingHelper so as to be used besides json
			$members = array();
			$groupIds = explode('-', $groups);
			foreach ($groupIds as $groupId) {
				$membersIds = JAccess::getUsersByGroup($groupId); //getUsersByGroup($groupId, true) recursively
				foreach ($membersIds as $userId) {
					$user = JFactory::getUser($userId);
					array_push($members, array('name'=>$user->name, 'email'=>$user->email));
				}
			}
			echo new JResponseJson($members);
		}
		catch(Exception $e)	{
			echo new JResponseJson($e);
		}
	}

}
