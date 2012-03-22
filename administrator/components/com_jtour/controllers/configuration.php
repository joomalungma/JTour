<?php
/**
 * @version 1.0.1
 * @package JTour! 1.0.1
 * @copyright (C) 2012 joomalungma.com
 * @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class JTourControllerConfiguration extends JTourController
{
	function __construct()
	{
		parent::__construct();
		$this->registerTask('apply', 'save');
	}
	
	/**
	 * Logic to save configuration
	 */
	function save()
	{

        // Check for request forgeries
        if(!$this->_input->get(JSession::getFormToken(),0,'BOOL')) jexit( 'Invalid Token' );

		// Get the model
		$model = $this->getModel('configuration');
		
		// Save
		$model->save();
		
		$tabposition = $this->_input->get('tabposition',0,'int');
		
		$task = $this->_input->get('task');
		if ($task == 'apply')
			$link = 'index.php?option=com_jtour&view=configuration&tabposition='.$tabposition;
		else
			$link = 'index.php?option=com_jtour';
		
		// Redirect
		$this->setRedirect($link, JText::_('JTOUR_CONFIGURATION_OK'));
	}
}
?>