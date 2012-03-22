<?php
/**
 * @version 1.0.1
 * @package JTour! 1.0.1
 * @copyright (C) 2012 joomalungma.com
 * @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class JTourModelTours extends JModel
{
    var $_data = null;
    var $_total = 0;
    var $_query = '';
    var $_pagination = null;
    var $_db = null;

    var $_id = 0;

    function __construct()
    {
        parent::__construct();
        $this->_db = JFactory::getDBO();
        $this->_query = $this->_buildQuery();

        $mainframe =& JFactory::getApplication();
        $option    = 'com_jtour';

        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest($option.'.tours.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option.'.tours.limitstart', 'limitstart', 0, 'int');

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState($option.'.tours.limit', $limit);
        $this->setState($option.'.tours.limitstart', $limitstart);
    }

    function _buildQuery()
    {
        $app =& JFactory::getApplication();
        $input = new JInput();

        $query = "SELECT * FROM #__jtour_tours WHERE 1";

        $filter_word = $input->get('search','','string');
        if (!empty($filter_word))
            $query .= " AND `name` LIKE '%".$filter_word."%'";

        $filter_state = $app->getUserStateFromRequest('jtour_filter_state', 'filter_state');
        if ($filter_state != '')
            $query .= " AND `published`='".($filter_state == 'U' ? '0' : '1')."'";

        $sortColumn = $input->get('filter_order', 'modified');
        $sortColumn = $this->_db->escape($sortColumn);

        $sortOrder = $input->get('filter_order_Dir', 'DESC');
        $sortOrder = $this->_db->escape($sortOrder);

        $query .= " ORDER BY ".$sortColumn." ".$sortOrder;
        return $query;
    }
    function getTours()
    {
        $option = 'com_jtour';

        if (empty($this->_data))
            $this->_data = $this->_getList($this->_query, $this->getState($option.'.tours.limitstart'), $this->getState($option.'.tours.limit'));
        return $this->_data;
    }
	
	function getPagination()
	{
		if (empty($this->_pagination))
		{
			$option = 'com_jtour';
			jimport('joomla.html.pagination');
			
			$this->_pagination = new JPagination($this->getTotal(), 0, 0);
		}
		
		return $this->_pagination;
	}
	
	function getTotal()
	{
		if (empty($this->_total))
			$this->_total = count($this->_data);
		
		return $this->_total;
	}
	
	function getTour()
	{
        $input = new JInput();
        $cid = $input->get('cid', 0, 'array');
		if (is_array($cid))
			$cid = $cid[0];
		$cid = (int) $cid;

		$row =&JTable::getInstance('tours','JTourTable');
        $row->load($cid);

		return $row;
	}
	
	function getId()
	{
		return $this->_id;
	}

	function remove($cids)
	{
		$cids = implode(',', $cids);

		$this->_db->setQuery("DELETE FROM #__jtour_tours WHERE `id` IN (".$cids.")");
		$this->_db->query();

		return true;
	}

    function publish($cid=array(), $publish=1)
    {
        if (!is_array($cid) || count($cid) > 0)
        {
            $publish = (int) $publish;
            $cids = implode(',', $cid);

            $query = "UPDATE #__jtour_tours SET `published`='".$publish."' WHERE `id` IN (".$cids.")"	;
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return $cid;
    }

	function save()
	{
        $row =&JTable::getInstance('tours','JTourTable');
		$post = JRequest::get('post');
        $input = new JInput();
        $var = array(
            'id'        => 'int',
	        'name'      => 'string',
	        'description' => 'HTML',
            'duration' => 'int',
            'price' => 'string',
            'currencyId' => 'string',
            'modified' => 'string',
            'published' => 'int',
            'cid'       =>array(),
        );
        $post = $input->getArray($var);

        $post['description'] = JRequest::getVar('description', '', 'post', 'none', JREQUEST_ALLOWRAW);

		if (!$row->bind($post))
			return JError::raiseWarning(500, $row->getError());

		if (empty($row->id))
			$row->ordering = $row->getNextOrder();
			
		if ($row->store())
		{
            return true;
		}
		else
		{
			JError::raiseWarning(500, $row->getError());
			return false;
		}
	}
}
?>