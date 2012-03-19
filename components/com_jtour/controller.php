<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class RSMembershipController extends JController
{
	var $_db;
	
	function __construct()
	{
		parent::__construct();
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'tables');
		
		$document =& JFactory::getDocument();
		// Add the css stylesheet
		$document->addStyleSheet(JURI::root(true).'/components/com_rsmembership/assets/css/rsmembership.css');
		// Add the javascript
		$document->addScript(JURI::root(true).'/components/com_rsmembership/assets/js/rsmembership.js');
		
		// Set the database object
		$this->_db =& JFactory::getDBO();
		
		RSMembershipHelper::readConfig();
	}
	
	/**
	 * Display the view
	 */
	function display()
	{
		parent::display();
	}
	
	function subscribe()
	{
		$user = JFactory::getUser();
		if (!$user->get('guest'))
		{
			$this->_db->setQuery("SELECT `unique` FROM #__rsmembership_memberships WHERE id='".JRequest::getInt('cid')."'");
			if ($this->_db->loadResult() > 0)
			{
				$this->_db->setQuery("SELECT id FROM #__rsmembership_membership_users WHERE user_id='".(int) $user->get('id')."' AND `membership_id`='".JRequest::getInt('cid')."'");
				if ($this->_db->loadResult())
				{
					JError::raiseWarning(500, JText::_('RSM_ALREADY_SUBSCRIBED'));
					return $this->setRedirect(JRoute::_('index.php?option=com_rsmembership', false));
				}
			}
		}

		JRequest::setVar('view', 'subscribe');
		JRequest::setVar('layout', 'default');
		
		parent::display();
	}
	
	function validatesubscribe()
	{
		$email = JRequest::getVar('email');
		if ($email)
		{
			$email = trim(strtolower($email));
			$this->_db->setQuery("SELECT id FROM #__users WHERE `email`='".$this->_db->getEscaped($email)."' LIMIT 1");
			$user_id = $this->_db->loadResult();
			if ($user_id)
			{
				$this->_db->setQuery("SELECT `unique` FROM #__rsmembership_memberships WHERE id='".JRequest::getInt('cid')."'");
				if ($this->_db->loadResult() > 0)
				{
					$this->_db->setQuery("SELECT id FROM #__rsmembership_membership_users WHERE user_id='".$user_id."' AND `membership_id`='".JRequest::getInt('cid')."'");
					if ($this->_db->loadResult())
					{
						JError::raiseWarning(500, JText::_('RSM_ALREADY_SUBSCRIBED'));
						return $this->setRedirect(JRoute::_('index.php?option=com_rsmembership', false));
					}
				}
			}
		}
		
		$model =& $this->getModel('subscribe');
		if (!$model->_bindData())
		{
			JError::raiseWarning(500, JText::_('RSM_PLEASE_TYPE_FIELDS'));
			$this->subscribe();
		}
		else
		{
			if (RSMembershipHelper::getConfig('one_page_checkout'))
			{
				$payment = JRequest::getVar('payment', 'none');
				JRequest::setVar('payment', $payment);
				return $this->paymentredirect();
			}
			
			JRequest::setVar('view', 'subscribe');
			JRequest::setVar('layout', 'preview');
			
			parent::display();
		}
	}
	
	function paymentredirect()
	{
		$payment = JRequest::getVar('payment', 'none');
		$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&task=payment&payment='.$payment, false));
	}
	
	function payment()
	{
		JRequest::setVar('view', 'subscribe');
		JRequest::setVar('layout', 'payment');
		
		parent::display();
	}
	
	function download()
	{
		JRequest::setVar('view', 'mymembership');
		JRequest::setVar('layout', 'default');
		
		parent::display();
	}
	
	function thankyou()
	{
		JRequest::setVar('view', 'thankyou');
		JRequest::setVar('layout', 'default');
		
		parent::display();
	}
	
	function upgrade()
	{
		$user = JFactory::getUser();
		$this->_db->setQuery("SELECT `unique` FROM #__rsmembership_memberships WHERE id='".JRequest::getInt('to_id')."'");
		if ($this->_db->loadResult() > 0)
		{
			$this->_db->setQuery("SELECT id FROM #__rsmembership_membership_users WHERE user_id='".(int) $user->get('id')."' AND `membership_id`='".JRequest::getInt('to_id')."'");
			if ($this->_db->loadResult())
			{
				JError::raiseWarning(500, JText::_('RSM_ALREADY_SUBSCRIBED'));
				return $this->setRedirect(JRoute::_('index.php?option=com_rsmembership', false));
			}
		}
		
		JRequest::setVar('view', 'upgrade');
		JRequest::setVar('layout', 'default');
		
		parent::display();
	}
	
	function upgradepaymentredirect()
	{
		$payment = JRequest::getVar('payment', 'none');		
		$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&task=upgradepayment&payment='.$payment, false));
	}
	
	function upgradepayment()
	{
		JRequest::setVar('view', 'upgrade');
		JRequest::setVar('layout', 'payment');
		
		parent::display();
	}
	
	function renew()
	{
		JRequest::setVar('view', 'renew');
		JRequest::setVar('layout', 'default');
		
		parent::display();
	}
	
	function renewpaymentredirect()
	{
		$payment = JRequest::getVar('payment', 'none');		
		$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&task=renewpayment&payment='.$payment, false));
	}
	
	function renewpayment()
	{
		JRequest::setVar('view', 'renew');
		JRequest::setVar('layout', 'payment');
		
		parent::display();
	}
	
	function addextra()
	{
		JRequest::setVar('view', 'addextra');
		JRequest::setVar('layout', 'default');
		
		parent::display();
	}
	
	function addextrapaymentredirect()
	{
		$payment = JRequest::getVar('payment', 'none');		
		$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&task=addextrapayment&payment='.$payment, false));
	}
	
	function addextrapayment()
	{
		JRequest::setVar('view', 'addextra');
		JRequest::setVar('layout', 'payment');
		
		parent::display();
	}
	
	function validateuser()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		$model =& $this->getModel('user');
		if (!$model->_bindData())
		{
			JError::raiseWarning(500, JText::_('RSM_PLEASE_TYPE_FIELDS'));
			JRequest::setVar('view', 'user');
			JRequest::setVar('layout', 'default');
			
			parent::display();
		}
		else
		{
			$model->save();
			// Redirect
			$this->setRedirect(JRoute::_('index.php?option=com_rsmembership&view=user', false), JText::_('RSM_USER_SAVED'));
		}
	}
	
	function checkUsername()
	{
		// Get vars
		$username 	  = JRequest::getCmd('username');
		$name		  = strtolower(JRequest::getVar('name'));
		if (strlen($name) < 2)
			$name = '';
		$email		  = strtolower(JRequest::getVar('email'));
		if (strlen($email) < 2)
			$email = '';
		
		// Keep the username intact
		$new_username = $username;
		
		// Local flags
		$used_name	  = false;
		$used_email	  = false;
		$reverted	  = false;
		
		// Return
		$suggestions  = array();
		
		// Check if username is available
		$this->_db->setQuery("SELECT id FROM #__users WHERE `username`='".$this->_db->getEscaped($new_username)."'");
		while (($num_rows = $this->_db->getNumRows($this->_db->query())) || count($suggestions) < 3)
		{
			// Add only if no rows are found
			if (!$num_rows && !in_array($new_username, $suggestions))
				$suggestions[] = $new_username;
			
			// Use a variation of the name, if available
			if ($name && !$used_name)
			{
				$used_name = true;
				$reverted = false;
				$new_username = str_replace('-', '_', JFilterOutput::stringURLSafe($name));
			}
			// Use a variation of the email, if available
			elseif ($email && !$used_email)
			{
				$used_email = true;
				$reverted = false;
				$new_username = str_replace('-', '_', JFilterOutput::stringURLSafe($email));
			}
			// Add random numbers to the username
			else
			{
				if (($used_name || $used_email) && !$reverted)
				{
					$reverted = true;
					$new_username = $username;
				}
				$new_username .= mt_rand(0,9);
			}
			
			if (strlen($new_username) < 2)
				$new_username = str_pad($new_username, 2, '_', STR_PAD_RIGHT);
			
			$this->_db->setQuery("SELECT id FROM #__users WHERE `username`='".$this->_db->getEscaped($new_username)."'");
		}
		
		echo implode('|', $suggestions);
		die();
	}
	
	function captcha()
	{
		$captcha_enabled = RSMembershipHelper::getConfig('captcha_enabled');
		if (!$captcha_enabled)
			return false;
			
		ob_end_clean();
		
		if ($captcha_enabled == 1)
		{
			if (!class_exists('JSecurImage'))
				require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_rsmembership'.DS.'helpers'.DS.'securimage'.DS.'securimage.php');
				
			$captcha = new JSecurImage();
			
			$captcha_lines = RSMembershipHelper::getConfig('captcha_lines');
			if ($captcha_lines)
				$captcha->num_lines = 8;
			else
				$captcha->num_lines = 0;
			
			$captcha_characters = RSMembershipHelper::getConfig('captcha_characters');
			$captcha->code_length = $captcha_characters;
			$captcha->image_width = 30*$captcha_characters + 50;
			$captcha->show();
		}
		
		die();
	}
}
?>