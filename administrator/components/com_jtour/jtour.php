<?php
/**
* @version 1.0.1
* @package jtour! 1.0.1
* @copyright (C) 2012 http://joomalungma.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once(JPATH_COMPONENT.DS.'controller.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'jtour.php');

JTourHelper::readConfig();
$input = new JInput();
// See if this is a request for a specific controller
$controller = $input->get('controller');
if (!empty($controller))
{
	require_once(JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
	$controller = 'jtourController'.$controller;
	$jtourController = new $controller();
}
else
	$jtourController = new jtourController();
	
$jtourController->execute($controller = $input->get('task'));

// Redirect if set
$jtourController->redirect();