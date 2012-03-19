<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class JTourViewjtour extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title('JTour!','jtour');
		
		JSubMenuHelper::addEntry(JText::_('COM_JTOUR_OVERVIEW'), 'index.php?option=com_jtour');
        JSubMenuHelper::addEntry(JText::_('COM_JTOUR_DOMAINS'), 'index.php?option=com_jtour&view=domains');
        JSubMenuHelper::addEntry(JText::_('COM_JTOUR_USERS'), 'index.php?option=com_jtour&view=users');
        JSubMenuHelper::addEntry(JText::_('COM_JTOUR_CONFIGURATION'), 'index.php?option=com_jtour&view=configuration');

		$this->assignRef('code', $this->get('code'));
		
		parent::display($tpl);
		parent::display('version');
	}
}