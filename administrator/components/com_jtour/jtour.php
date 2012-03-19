<?php
/**
* @version 1.0.0
* @package DManager! 1.0.0
* @copyright (C) 2012 http://www.pererva.su
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once(JPATH_COMPONENT.DS.'controller.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'dmanager.php');

DManagerHelper::readConfig();

// See if this is a request for a specific controller
$controller = JRequest::getCmd('controller');
if (!empty($controller))
{
	require_once(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
	$controller = 'DManagerController'.$controller;
	$DManagerController = new $controller();
}
else
	$DManagerController = new DManagerController();
	
$DManagerController->execute(JRequest::getCmd('task'));

// Redirect if set
$DManagerController->redirect();