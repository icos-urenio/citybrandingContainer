<?php

/**
 * @version     1.0.0
 * @package     com_citybranding
 * @subpackage  mod_citybranding
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU AFFERO GENERAL PUBLIC LICENSE Version 3; see LICENSE
 * @author      Ioannis Tsampoulatidis <tsampoulatidis@gmail.com> - https://github.com/itsam
 */
defined('_JEXEC') or die;


// Check for component
if (!JComponentHelper::getComponent('com_citybranding', true)->enabled)
{
	echo '<div class="alert alert-danger">Improve My City component is not enabled</div>';
	return;
}

$jinput = JFactory::getApplication()->input;
$option = $jinput->get('option', null);
$view = $jinput->get('view', null);

if ($option == 'com_citybranding' && $view != 'pois'){
	if($params->get('show_on_details') == 0){
		$module->showtitle = false;
		return;
	}
}

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$doc = JFactory::getDocument();
$doc->addStyleSheet(JURI::base() . '/modules/mod_citybrandingfilters/assets/css/style.css');
$doc->addStyleSheet(JURI::base() . '/modules/mod_citybrandingfilters/assets/css/ns-default.css');
//$doc->addStyleSheet(JURI::base() . '/modules/mod_citybrandingfilters/assets/css/ns-style-bar.css');
$doc->addStyleSheet(JURI::base() . '/modules/mod_citybrandingfilters/assets/css/ns-style-attached.css');
$doc->addScript(JURI::base() . '/modules/mod_citybrandingfilters/assets/js/classie.js');
$doc->addScript(JURI::base() . '/modules/mod_citybrandingfilters/assets/js/modernizr.custom.js');
$doc->addScript(JURI::base() . '/modules/mod_citybrandingfilters/assets/js/notificationFx.js');
$doc->addScript(JURI::base() . '/modules/mod_citybrandingfilters/assets/js/script.js');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_citybrandingfilters', $params->get('layout', 'default'));
