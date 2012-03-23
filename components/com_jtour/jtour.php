<?php
/**
* @version 1.0.1
    * @package JTour! 1.0.1
    * @copyright (C) 2012 joomalungma.com
    * @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once(JPATH_COMPONENT.DS.'controller.php');
require_once(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_jtour' . DS . 'helpers' . DS . 'jtour.php');

JTourHelper::readConfig();

$input = JFactory::getApplication()->input;
// See if this is a request for a specific controller
$controller = $input->get('controller');
$controllers = array('memberships');
if (!empty($controller) && in_array($controller, $controllers))
{
	require_once(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
	$controller = 'JTourController'.$controller;
	$jtourController = new $controller();
}
else
	$jtourController = new jtourController();
	
$jtourController->execute($input->get('task'));

// Redirect if set
$jtourController->redirect();