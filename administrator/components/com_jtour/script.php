<?php

defined('_JEXEC') or die('Restricted access');


class com_jtourInstallerScript
{
    protected $_jtour_extension = 'com_jtour';
	function install($parent) {}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) {}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) {}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
        if(in_array($type, array('install','discover_install'))) {
            $this->_bugfixDBFunctionReturnedNoError();
        } else {
            $this->_bugfixCantBuildAdminMenus();
        }
    }
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{

	}
    private function _bugfixCantBuildAdminMenus()
    	{
    		$db = JFactory::getDbo();
    		
    		// If there are multiple #__extensions record, keep one of them
    		$query = $db->getQuery(true);
    		$query->select('extension_id')
    			->from('#__extensions')
    			->where($db->nameQuote('element').' = '.$db->Quote($this->_jtour_extension));
    		$db->setQuery($query);
    		$ids = $db->loadResultArray();
    		if(count($ids) > 1) {
    			asort($ids);
    			$extension_id = array_shift($ids); // Keep the oldest id
    			
    			foreach($ids as $id) {
    				$query = $db->getQuery(true);
    				$query->delete('#__extensions')
    					->where($db->nameQuote('extension_id').' = '.$db->Quote($id));
    				$db->setQuery($query);
    				$db->query();
    			}
    		}
    		
    		// @todo
    		
    		// If there are multiple assets records, delete all except the oldest one
    		$query = $db->getQuery(true);
    		$query->select('id')
    			->from('#__assets')
    			->where($db->nameQuote('name').' = '.$db->Quote($this->_jtour_extension));
    		$db->setQuery($query);
    		$ids = $db->loadObjectList();
    		if(count($ids) > 1) {
    			asort($ids);
    			$asset_id = array_shift($ids); // Keep the oldest id
    			
    			foreach($ids as $id) {
    				$query = $db->getQuery(true);
    				$query->delete('#__assets')
    					->where($db->nameQuote('id').' = '.$db->Quote($id));
    				$db->setQuery($query);
    				$db->query();
    			}
    		}
    
    		// Remove #__menu records for good measure!
    		$query = $db->getQuery(true);
    		$query->select('id')
    			->from('#__menu')
    			->where($db->nameQuote('type').' = '.$db->Quote('component'))
    			->where($db->nameQuote('menutype').' = '.$db->Quote('main'))
    			->where($db->nameQuote('link').' LIKE '.$db->Quote('index.php?option='.$this->_jtour_extension));
    		$db->setQuery($query);
    		$ids1 = $db->loadResultArray();
    		if(empty($ids1)) $ids1 = array();
    		$query = $db->getQuery(true);
    		$query->select('id')
    			->from('#__menu')
    			->where($db->nameQuote('type').' = '.$db->Quote('component'))
    			->where($db->nameQuote('menutype').' = '.$db->Quote('main'))
    			->where($db->nameQuote('link').' LIKE '.$db->Quote('index.php?option='.$this->_jtour_extension.'&%'));
    		$db->setQuery($query);
    		$ids2 = $db->loadResultArray();
    		if(empty($ids2)) $ids2 = array();
    		$ids = array_merge($ids1, $ids2);
    		if(!empty($ids)) foreach($ids as $id) {
    			$query = $db->getQuery(true);
    			$query->delete('#__menu')
    				->where($db->nameQuote('id').' = '.$db->Quote($id));
    			$db->setQuery($query);
    			$db->query();
    		}
    	}
    private function _bugfixDBFunctionReturnedNoError()
    	{
    		$db = JFactory::getDbo();
    			
    		// Fix broken #__assets records
    		$query = $db->getQuery(true);
    		$query->select('id')
    			->from('#__assets')
    			->where($db->nameQuote('name').' = '.$db->Quote($this->_jtour_extension));
    		$db->setQuery($query);
    		$ids = $db->loadResultArray();
    		if(!empty($ids)) foreach($ids as $id) {
    			$query = $db->getQuery(true);
    			$query->delete('#__assets')
    				->where($db->nameQuote('id').' = '.$db->Quote($id));
    			$db->setQuery($query);
    			$db->query();
    		}
    
    		// Fix broken #__extensions records
    		$query = $db->getQuery(true);
    		$query->select('extension_id')
    			->from('#__extensions')
    			->where($db->nameQuote('element').' = '.$db->Quote($this->_jtour_extension));
    		$db->setQuery($query);
    		$ids = $db->loadResultArray();
    		if(!empty($ids)) foreach($ids as $id) {
    			$query = $db->getQuery(true);
    			$query->delete('#__extensions')
    				->where($db->nameQuote('extension_id').' = '.$db->Quote($id));
    			$db->setQuery($query);
    			$db->query();
    		}
    
    		// Fix broken #__menu records
    		$query = $db->getQuery(true);
    		$query->select('id')
    			->from('#__menu')
    			->where($db->nameQuote('type').' = '.$db->Quote('component'))
    			->where($db->nameQuote('menutype').' = '.$db->Quote('main'))
    			->where($db->nameQuote('link').' LIKE '.$db->Quote('index.php?option='.$this->_jtour_extension));
    		$db->setQuery($query);
    		$ids = $db->loadResultArray();
    		if(!empty($ids)) foreach($ids as $id) {
    			$query = $db->getQuery(true);
    			$query->delete('#__menu')
    				->where($db->nameQuote('id').' = '.$db->Quote($id));
    			$db->setQuery($query);
    			$db->query();
    		}
    	}
}

;?>