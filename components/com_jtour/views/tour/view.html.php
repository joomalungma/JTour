<?php
/**
 * @version 1.0.1
 * @package JTour! 1.0.1
 * @copyright (C) 2012 joomalungma.com
 * @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class JTourViewTours extends JView
{
    function display($tpl = null)
    {
        JToolBarHelper::title('JTour!','jtour');

        JSubMenuHelper::addEntry(JText::_('JTOUR_OVERVIEW'), 'index.php?option=com_jtour');
        JSubMenuHelper::addEntry(JText::_('JTOUR_TOURS'), 'index.php?option=com_jtour&view=tours', true);
        JSubMenuHelper::addEntry(JText::_('JTOUR_EXCURSIONS'), 'index.php?option=com_jtour&view=excursions');
        JSubMenuHelper::addEntry(JText::_('JTOUR_ORDERS'), 'index.php?option=com_jtour&view=orders');
        JSubMenuHelper::addEntry(JText::_('JTOUR_PAYMENTS'), 'index.php?option=com_jtour&view=payments');
        JSubMenuHelper::addEntry(JText::_('JTOUR_CONFIGURATION'), 'index.php?option=com_jtour&view=configuration');

        $input = new JInput();
        $app = JFactory::getApplication();

        $task = $input->get('task','');

        if ($task == 'edit')
        {
            JToolBarHelper::title('JTour! <small>['.JText::_('JTOUR_EDIT_TOUR').']</small>','jtour');

            JToolBarHelper::apply();
            JToolBarHelper::save();
            JToolBarHelper::cancel();

            $this->assignRef('editor', JFactory::getEditor());

            $row = $this->get('tour');
            $this->assignRef('row', $row);

            $lists['published']	    = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $row->published);
            $lists['duration']	    = JHTML::_('select.integerlist', 1,30,1, 'class="inputbox"', $row->duration);

            $this->assignRef('currency', JTourHelper::getConfig('currencyDefault'));
            $this->assignRef('lists', $lists);
        }
        else
        {
            JToolBarHelper::addNew('edit');
            JToolBarHelper::editList('edit');
            JToolBarHelper::spacer();

            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
            JToolBarHelper::spacer();

            JToolBarHelper::deleteList('JTOUR_CONFIRM_DELETE');

            $filter_state = $app->getUserStateFromRequest('jtour.filter_state', 'filter_state');
            $app->setUserState('jtour.filter_state', $filter_state);
            $lists['state']	= JHTML::_('grid.state', $filter_state);
            $this->assignRef('lists', $lists);

            $this->assignRef('sortColumn', $input->get('filter_order','date_added'));
            $this->assignRef('sortOrder', $input->get('filter_order_Dir','DESC'));

            $this->assignRef('tours', $this->get('tours'));
            $this->assignRef('pagination', $this->get('pagination'));

            $this->assignRef('editor', JFactory::getEditor());


            $filter_word = $input->get('search', '','string');
            $this->assignRef('filter_word', $filter_word);
            $this->assignRef('tours',$this->get('tours'));
        }

        parent::display($tpl);
    }
}