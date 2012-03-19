<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class DmanagerViewDManager extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title('Domain Manager!','dmanager');
		
		JSubMenuHelper::addEntry(JText::_('COM_DMANAGER_OVERVIEW'), 'index.php?option=com_dmanager');
        JSubMenuHelper::addEntry(JText::_('COM_DMANAGER_DOMAINS'), 'index.php?option=com_dmanager&view=domains');
        JSubMenuHelper::addEntry(JText::_('COM_DMANAGER_USERS'), 'index.php?option=com_dmanager&view=users');
        JSubMenuHelper::addEntry(JText::_('COM_DMANAGER_CONFIGURATION'), 'index.php?option=com_dmanager&view=configuration');

		$this->assignRef('code', $this->get('code'));
		
		parent::display($tpl);
		parent::display('version');
	}
}