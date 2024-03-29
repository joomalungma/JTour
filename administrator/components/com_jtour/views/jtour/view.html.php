<?php
/**
 * @version 1.0.1
 * @package JTour! 1.0.1
 * @copyright (C) 2012 joomalungma.com
 * @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class JTourViewJTour extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title('JTour!','jtour');
		
		JSubMenuHelper::addEntry(JText::_('JTOUR_OVERVIEW'), 'index.php?option=com_jtour', true);
        JSubMenuHelper::addEntry(JText::_('JTOUR_TOURS'), 'index.php?option=com_jtour&view=tours');
        JSubMenuHelper::addEntry(JText::_('JTOUR_EXCURSIONS'), 'index.php?option=com_jtour&view=excursions');
        JSubMenuHelper::addEntry(JText::_('JTOUR_ORDERS'), 'index.php?option=com_jtour&view=orders');
        JSubMenuHelper::addEntry(JText::_('JTOUR_PAYMENTS'), 'index.php?option=com_jtour&view=payments');
        JSubMenuHelper::addEntry(JText::_('JTOUR_CONFIGURATION'), 'index.php?option=com_jtour&view=configuration');

        parent::display($tpl);
		parent::display('version');
	}
}