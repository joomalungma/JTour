<?php
/**
* @version 1.0.0
* @package DManager! 1.0.0
* @copyright (C) 2012 http://www.pererva.su
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class DManagerController extends JController
{
	var $_db;
	
	function __construct()
	{
		parent::__construct();
		$document =& JFactory::getDocument();
		// Add the css stylesheet
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_DManager/assets/css/DManager.css');
		
		if (DManagerHelper::isJ16())
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_DManager/assets/css/DManager16.css');
		
		// Set the database object
		$this->_db =& JFactory::getDBO();
		
		DManagerHelper::readConfig();
	}
	
	/**
	 * Display the view
	 */
	function display()
	{
		parent::display();
	}
	
	function saveRegistration()
	{
		$code = JRequest::getVar('global_register_code');
		$code = $this->_db->getEscaped($code);
		if (!empty($code))
		{
			$this->_db->setQuery("UPDATE #__DManager_configuration SET `value`='".$code."' WHERE `name`='global_register_code'");
			$this->_db->query();
			$this->setRedirect('index.php?option=com_DManager&view=updates', JText::_('RSM_LICENSE_SAVED'));
		}
		else
			$this->setRedirect('index.php?option=com_DManager&view=configuration');
	}
	
	function editPlugin()
	{
		$id 	 = JRequest::getInt('cid');
		$context = 'com_plugins.edit.plugin';
		$app 	 = &JFactory::getApplication();
		$values  = (array) $app->getUserState($context.'.id');
		if (!in_array($id, $values))
		{
			$values[] = $id;
			$app->setUserState($context.'.id', $values);
		}
			
		$this->setRedirect('index.php?option=com_plugins&view=plugin&layout=edit&extension_id='.$id);
	}
	
	function ajax_addmembershipshared()
	{
		JRequest::setVar('view', 'memberships');
		JRequest::setVar('layout', 'edit_shared');
		
		parent::display();
	}
	
	function ajax_addsubscriberfiles()
	{
		JRequest::setVar('view', 'memberships');
		JRequest::setVar('layout', 'edit_files');
		
		parent::display();
	}
	
	function ajax_addextravaluefolders()
	{
		JRequest::setVar('view', 'extravalues');
		JRequest::setVar('layout', 'edit_shared');
		
		parent::display();
	}
	
	function ajax_addmemberships()
	{
		JRequest::setVar('view', 'users');
		JRequest::setVar('layout', 'edit_memberships');
		
		parent::display();
	}
	
	function ajax_date()
	{
		$date = DManagerHelper::getCurrentDate(JRequest::getInt('date'));
		echo date(DManagerHelper::getConfig('date_format'), $date);
		exit();
	}
}
?>