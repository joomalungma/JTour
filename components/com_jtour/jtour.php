<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once(JPATH_COMPONENT.DS.'controller.php');
require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_rsmembership' . DS . 'helpers' . DS . 'dmanager.php');

RSMembershipHelper::readConfig();

// See if this is a request for a specific controller
$controller = JRequest::getCmd('controller');
$controllers = array('memberships');
if (!empty($controller) && in_array($controller, $controllers))
{
	require_once(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
	$controller = 'RSMembershipController'.$controller;
	$RSMembershipController = new $controller();
}
else
	$RSMembershipController = new RSMembershipController();
	
$RSMembershipController->execute(JRequest::getCmd('task'));

// Redirect if set
$RSMembershipController->redirect();
?>