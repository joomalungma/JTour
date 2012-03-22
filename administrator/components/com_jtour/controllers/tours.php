<?php
/**
 * @version 1.0.1
 * @package JTour! 1.0.1
 * @copyright (C) 2012 joomalungma.com
 * @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JTourControllerTours extends JTourController
{
	function __construct()
	{
		parent::__construct();
		
		// Tours Tasks
        $this->registerTask('orderup', 'move');
        $this->registerTask('orderdown', 'move');
        $this->registerTask('apply', 'save');
        $this->registerTask('publish', 'changestatus');
        $this->registerTask('unpublish', 'changestatus');
	}
	
	function cancel()
	{
		$this->setRedirect('index.php?option=com_jtour&view=tours');
	}
	
	/**
	 * tours Tasks
	 */
	// tours - Edit
	function edit()
	{
        $this->_input->set('view', 'tours');
        $this->_input->set('layout', 'edit');
		
		parent::display();
	}
    function changestatus()
    {
        // Check for request forgeries
        JRequest::checkToken() or jexit('Invalid Token');

        // Get the model
        $model = $this->getModel('tours');

        // Get the selected items
        $cid = $this->_input->get('cid',array(0),'array');

        // Get the task
        $cid = $this->_input->get('task');

        // Force array elements to be integers
        JArrayHelper::toInteger($cid, array(0));

        $msg = '';

        // No items are selected
        if (!is_array($cid) || count($cid) < 1)
            JError::raiseWarning(500, JText::_('SELECT ITEM PUBLISH'));
        // Try to publish the item
        else
        {
            $value = $task == 'publish' ? 1 : 0;
            if (!$model->publish($cid, $value))
                JError::raiseError(500, $model->getError());

            $total = count($cid);
            if ($value)
                $msg = JText::sprintf('COM_TOURS_PUBLISHED', $total);
            else
                $msg = JText::sprintf('COM_TOURS_UNPUBLISHED', $total);

            // Clean the cache, if any
            $cache =& JFactory::getCache('com_jtour');
            $cache->clean();
        }

        // Redirect
        $this->setRedirect('index.php?option=com_jtour&view=tours', $msg);
    }
    
	
	// tours - Remove
	function remove()
	{
        // Check for request forgeries
        if(!$this->_input->get(JSession::getFormToken(),0,'BOOL')) jexit( 'Invalid Token' );

        $app = JFactory::getApplication();
		// Get the model
		$model = $this->getModel('tours');
		
		// Get the selected items
        $cid = $this->_input->get('cid',array(0),'array');


		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		$msg = '';
		
		// No items are selected
		if (!is_array($cid) || count($cid) < 1)
            $app->enqueueMessage(JText::_('SELECT ITEM DELETE'), 'warning');
		// Try to remove the item
		else
		{
			$model->remove($cid);
			
			$total = count($cid);
			$msg = JText::sprintf('JTOUR_TOUR_DELETED', $total);
			
			// Clean the cache, if any
			$cache =& JFactory::getCache('com_jtour');
			$cache->clean();
		}
		
		// Redirect
		$this->setRedirect('index.php?option=com_jtour&view=tours', $msg);
	}

	// tours - Save
	function save()
	{
        // Check for request forgeries
        if(!$this->_input->get(JSession::getFormToken(),0,'BOOL')) jexit( 'Invalid Token' );

		// Get the model
		$model = $this->getModel('tours');

		// Save
		$result = $model->save();
        $cid = $this->_input->get('id','','int');

        $task = $this->_input->get('task');
		switch($task)
		{
			case 'apply':
				$link = 'index.php?option=com_jtour&controller=tours&task=edit&cid='.$cid;
				if ($result)
					$this->setRedirect($link, JText::_('JTOUR_TOUR_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('JTOUR_TOUR_SAVED_ERROR'));
			break;

			case 'save':
				$link = 'index.php?option=com_jtour&view=tours';
				if ($result)
					$this->setRedirect($link, JText::_('JTOUR_TOUR_SAVED_OK'));
				else
					$this->setRedirect($link, JText::_('JTOUR_TOUR_SAVED_ERROR'));
			break;
		}
	}
	
	// Save Ordering
	function saveOrder()
	{
		// Check for request forgeries
		if(!$this->_input->get(JSession::getFormToken(),0,'BOOL')) jexit( 'Invalid Token' );
		
		// Get the table instance
		$row =& JTable::getInstance('tours','Table');
		
		// Get the selected items
        $cid = $this->_input->get('cid',array(0),'array');
		
		// Get the ordering
        $order = $this->_input->get('order',array(0),'array');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));
		
		// Load each element of the array
		for ($i=0;$i<count($cid);$i++)
		{
			// Load the item
			$row->load($cid[$i]);
			
			// Set the new ordering only if different
			if ($row->ordering != $order[$i])
			{	
				$row->ordering = $order[$i];
				if (!$row->store()) 
				{
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		// Redirect
		$this->setRedirect('index.php?option=com_jtour&view=tours', JText::_('JTOUR_TOURS_ORDERED'));
	}
	
	// Move Up/Down
	function move() 
	{
		// Check for request forgeries
		if(!$this->_input->get(JSession::getFormToken(),0,'BOOL')) jexit( 'Invalid Token' );
		
		// Get the table instance
		$row =& JTable::getInstance('tours','Table');
		
		// Get the selected items
        $cid = $this->_input->get('cid',array(0),'array');
		
		// Get the task
		$task = $this->_input->get('task');
		
		// Force array elements to be integers
		JArrayHelper::toInteger($cid, array(0));
		
		// Set the direction to move
		$direction = $task == 'orderup' ? -1 : 1;
		
		// Can move only one element
		if (is_array($cid))	$cid = $cid[0];
		
		// Load row
		if (!$row->load($cid)) 
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		// Move
		$row->move($direction);
	
		// Redirect
		$this->setRedirect('index.php?option=com_jtour&view=tours');
	}
}
?>