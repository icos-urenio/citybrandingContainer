<?php

/**
 * @version     1.0.0
 * @package     com_citybranding
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     GNU AFFERO GENERAL PUBLIC LICENSE Version 3; see LICENSE
 * @author      Ioannis Tsampoulatidis <tsampoulatidis@gmail.com> - https://github.com/itsam
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Citybranding records.
 */
class CitybrandingModelLogs extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'action', 'a.action',
                'poiid', 'a.poiid',
                'stepid', 'a.stepid',
                'description', 'a.description',
                'created', 'a.created',
                'updated', 'a.updated',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'created_by', 'a.created_by',
                'language', 'a.language',

            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        //Filtering stepid
        $this->setState('filter.stepid', $app->getUserStateFromRequest($this->context.'.filter.stepid', 'filter_stepid', '', 'string'));

		//Filtering poiid
		$this->setState('filter.poiid', $app->getUserStateFromRequest($this->context.'.filter.poiid', 'filter_poiid', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_citybranding');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.created', 'desc');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param	string		$id	A prefix for the store id.
     * @return	string		A store id.
     * @since	1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'DISTINCT a.*'
                )
        );
        $query->from('`#__citybranding_log` AS a');

        
		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		// Join over the foreign key 'poiid'
		$query->select('#__citybranding_pois_1382355.title AS pois_title_1382355');
		$query->join('LEFT', '#__citybranding_pois AS #__citybranding_pois_1382355 ON #__citybranding_pois_1382355.id = a.poiid');
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
        // Join over the citybranding steps.
        $query->select('st.title AS stepid_title, st.stepcolor AS stepid_color')
            ->join('LEFT', '#__citybranding_steps AS st ON st.id = a.stepid');
        

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                
            }
        }

		//Filtering poiid
		$filter_poiid = $this->state->get("filter.poiid");
		if ($filter_poiid) {
			$query->where("a.poiid = '".$db->escape($filter_poiid)."'");
		}

        //Filtering stepid
        if ($stepid = $this->getState('filter.stepid'))
        {
            $query->where('a.stepid = ' . (int) $stepid);
        }

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }

        
        return $query;
    }

    public function getItems() {
        $items = parent::getItems();
        
		foreach ($items as $oneItem) {

			if (isset($oneItem->poiid)) {
				$values = explode(',', $oneItem->poiid);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('title')
							->from('`#__citybranding_pois`')
							->where('id = ' . $db->quote($db->escape($value)));
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->title;
					}
				}

			$oneItem->poiid = !empty($textValue) ? implode(', ', $textValue) : $oneItem->poiid;

			}

            /* doh
			if (isset($oneItem->stepid)) {
				$values = explode(',', $oneItem->stepid);

				$textValue = array();
				foreach ($values as $value){
					if(!empty($value)){
						$db = JFactory::getDbo();
						$query = "SELECT id, title AS value FROM #__citybranding_steps HAVING id LIKE '" . $value . "'";
						$db->setQuery($query);
						$results = $db->loadObject();
						if ($results) {
							$textValue[] = $results->value;
						}
					}
				}

			$oneItem->stepid = !empty($textValue) ? implode(', ', $textValue) : $oneItem->stepid;

			}
            */
		}
        return $items;
    }

    public function getItemsByPoi($id = null)
    {

        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select('a.action, a.description, a.created');
        $query->from('`#__citybranding_log` AS a');

        //-- no point to get the poi title...
        // Join over the foreign key 'poiid'
        //$query->select('#__citybranding_pois.title AS poi_title');
        //$query->join('LEFT', '#__citybranding_pois ON #__citybranding_pois.id = a.poiid');

        // Join over the user field 'created_by'
        $query->select('u.name AS created_by');
        $query->join('LEFT', '#__users AS u ON u.id = a.created_by');

        // Join over the citybranding steps.
        $query->select('st.title AS stepid_title, st.stepcolor AS stepid_color')
              ->join('LEFT', '#__citybranding_steps AS st ON st.id = a.stepid');
        
        $query->order('a.created', 'desc');
        $query->where('a.poiid = '.($id == null ? $this->getState('poi.id') : $id) );


        $db->setQuery($query);
        $results = $db->loadAssocList();        
        return $results;
    }

}
