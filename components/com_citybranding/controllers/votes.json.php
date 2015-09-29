<?php
/**
 * @version     1.0.0
 * @package     com_citybranding
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU AFFERO GENERAL PUBLIC LICENSE Version 3; see LICENSE
 * @author      Ioannis Tsampoulatidis <tsampoulatidis@gmail.com> - https://github.com/itsam
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Pois list controller class.
 */
class CitybrandingControllerVotes extends CitybrandingController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Votes', $prefix = 'CitybrandingModel')
	{
		JModelLegacy::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/models');
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function add()
	{
		if(!JSession::checkToken('get')){
			echo new JResponseJson('Invalid Token');
			jexit();
		}
		
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$poi_id = $jinput->get('poi_id', null);
		$user_id = $jinput->get('user_id', null);

		try {
			$result = $this->getModel()->add($poi_id, $user_id);
			//print_r($result);
			echo new JResponseJson($result);
		}
		catch(Exception $e)	{
			echo new JResponseJson($e);
		}
	}	

/*	public function add()
	{
		JRequest::checkToken('get') or jexit('Invalid Token');
		
		$user =& JFactory::getUser();
		if(!$user->guest)
		{
			//update vote
			$model = $this->getModel('poi');
			if($model->getHasVoted() == 0){
				$votes = $model->vote(); 
				if($votes == -1){
					$ret['msg'] = JText::_('VOTE_ERROR');
					echo json_encode($ret);
				}
			
				$ret['msg'] = JText::_('VOTE_ADDED');
				$ret['votes'] = $votes;
				echo json_encode($ret);
			}
			else{
				$ret['msg'] = JText::_('ALREADY_VOTED');
				echo json_encode($ret);			
			}
		}
		else {
			//$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
			$ret['msg'] = JText::_('ONLY_LOGGED_VOTE');
			echo json_encode($ret);
		}
		//return 0;
	}	
*/

}

