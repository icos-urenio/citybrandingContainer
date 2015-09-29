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
?>

<?php $category_filters = ModCitybrandingfiltersHelper::getCategoryFilters();
foreach ($category_filters as $filter) {
	echo $filter;
}
?>

<button class="btn btn-primary"><?php echo JText::_('Apply');?></button>
