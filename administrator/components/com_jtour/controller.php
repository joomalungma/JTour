<?php
/**
* @version 1.0.0
* @package JTour! 1.0.0
* @copyright (C) 2012 http://joomalungma.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JTourController extends JController
{
	var $_db;
    var $_input;
	
	function __construct()
	{
		parent::__construct();
		$document =& JFactory::getDocument();
		// Add the css stylesheet
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_jtour/assets/css/jtour.css');
		
		// Set the database object
		$this->_db =& JFactory::getDBO();
		$this->_input = JFactory::getApplication()->input;

		JTourHelper::readConfig();
	}
	
	/**
	 * Display the view
	 */
	function display()
	{
		parent::display();
	}
}