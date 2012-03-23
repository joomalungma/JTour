<?php
/**
 * @version 1.0.0
 * @package JTour! 1.0.0
 * @copyright (C) 2012 http://joomalungma.com
 * @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access');

define('_JTOUR_VERSION', '17');
define('_JTOUR_VERSION_LONG', '1.0.0');
define('_JTOUR_PRODUCT', 'JTour');
define('_JTOUR_COPYRIGHT', '&copy;2012 joomalungma.com');
define('_JTOUR_LICENSE', 'GPL Commercial License');
define('_JTOUR_AUTHOR', '<a href="http://joomalungma.com" target="_blank">Joomalungma</a>');

class JTourHelper
{
	function readConfig($reload=false)
	{
		static $jtour_config;
		
		if (!is_object($jtour_config) || $reload)
		{
			$jtour_config = new stdClass();
			
			$db =& JFactory::getDBO();
			$db->setQuery("SELECT * FROM `#__jtour_configuration`");
			$config = $db->loadObjectList();
			if (!empty($config))
				foreach ($config as $config_item)
					$jtour_config->{$config_item->name} = $config_item->value;
		}
		
		return $jtour_config;
	}
	
	function getConfig($name = null)
	{
		$config = jtourHelper::readConfig();
		
		if ($name != null)
		{
			if (isset($config->$name))
				return $config->$name;
			else
				return false;
		}
		else
			return $config;
	}

    function isJ16()
	{
		jimport('joomla.version');
		$version = new JVersion();
		return $version->isCompatible('1.6.0');
	}
    static function getWeek()
    {
        $week = array(JText::_('SUNDAY'),JText::_('MONDAY'),JText::_('TUESDAY'),JText::_('WEDNESDAY'),JText::_('THURSDAY'),JText::_('FRIDAY'),JText::_('SATURDAY'));
        return $week;
    }
	
	function getCurrentDate($date=null)
	{
		$config = JFactory::getConfig();
		
		if (jtourHelper::isJ16())
		{			
			$config = JFactory::getConfig();
			date_default_timezone_set($config->get('offset'));
			
			$unix = $date;
		}
		else
		{
			$date = JFactory::getDate($date, -$config->get('offset'));
			$unix = $date->toUnix();
		}
		
		return $unix;
	}
	
	function checkPatches($type)
	{
		jimport('joomla.filesystem.file');
		
		$file = jtourHelper::getPatchFile($type);
		
		$buffer = JFile::read($file);
		if (strpos($buffer, 'jtourHelper') !== false)
			return true;
			
		return false;
	}
	
	function getPatchFile($type)
	{
		if ($type == 'menu')
		{
			if (jtourHelper::isJ16())
				return JPATH_SITE.DS.'modules'.DS.'mod_menu'.DS.'helper.php';
			else
				return JPATH_SITE.DS.'modules'.DS.'mod_mainmenu'.DS.'helper.php';
		}
		elseif ($type == 'module')
			return JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'application'.DS.'module'.DS.'helper.php';
	}
	
	function checkMenuShared(&$rows)
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		
		$db->setQuery("SELECT `membership_id` FROM #__jtour_membership_users WHERE `user_id`='".$user->get('id')."' AND `status`='0'");
		$memberships = $db->loadResultArray();
		
		$db->setQuery("SELECT `extras` FROM #__jtour_membership_users WHERE `user_id`='".$user->get('id')."' AND `status`='0'");
		$extras = array();
		$extras_array = $db->loadResultArray();
		if (is_array($extras_array))
			foreach ($extras_array as $extra)
			{
				if (empty($extra)) continue;
				
				$extra = explode(',', $extra);
				$extras = array_merge($extras, $extra);
			}
		
		$db->setQuery("SELECT `membership_id`, `params` FROM #__jtour_membership_shared WHERE `type`='menu' AND `published`='1'");
		$shared = $db->loadObjectList();

		$db->setQuery("SELECT `extra_value_id`, `params` FROM #__jtour_extra_value_shared WHERE `type`='menu' AND `published`='1'");
		$shared2 = $db->loadObjectList();
		if (!empty($shared2))
			$shared = array_merge($shared, $shared2);
		
		$allowed = array();
		foreach ($shared as $share)
		{
			$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
			$where = isset($share->membership_id) ? $memberships : $extras;
			
			if (in_array($share->{$what}, $where))
				$allowed[] = $share->params;
		}
		
		foreach ($shared as $share)
		{
			$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
			$where = isset($share->membership_id) ? $memberships : $extras;
			
			if (!in_array($share->params, $allowed))
			{
				if (is_array($rows))
				foreach ($rows as $i => $row)
					if ($row->id == $share->params)
					{
						unset($rows[$i]);
						break;
					}
			}
		}
	}
	
	function getModulesWhere()
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		
		$db->setQuery("SELECT `membership_id` FROM #__jtour_membership_users WHERE `user_id`='".$user->get('id')."' AND `status`='0'");
		$memberships = $db->loadResultArray();
			
		$db->setQuery("SELECT `extras` FROM #__jtour_membership_users WHERE `user_id`='".$user->get('id')."' AND `status`='0'");
		$extras = array();
		$extras_array = $db->loadResultArray();
		if (is_array($extras_array))
			foreach ($extras_array as $extra)
			{
				if (empty($extra)) continue;
				
				$extra = explode(',', $extra);
				$extras = array_merge($extras, $extra);
			}
		
		$db->setQuery("SELECT `membership_id`, `params` FROM #__jtour_membership_shared WHERE `type`='module' AND `published`='1'");
		$shared = $db->loadObjectList();
		if (empty($shared))
			$shared = array();
		
		$db->setQuery("SELECT `extra_value_id`, `params` FROM #__jtour_extra_value_shared WHERE `type`='module' AND `published`='1'");
		$shared2 = $db->loadObjectList();
		if (!empty($shared2))
			$shared = array_merge($shared, $shared2);
		
		$allowed = array();
		$not_allowed = array();
		foreach ($shared as $share)
		{
			$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
			$where = isset($share->membership_id) ? $memberships : $extras;
			
			if (in_array($share->{$what}, $where))
				$allowed[] = $share->params;
		}
		
		foreach ($shared as $share)
		{
			if (!in_array($share->params, $allowed))
				$not_allowed[] = $share->params;
		}
		
		if ($not_allowed)
			return (!jtourHelper::isJ16() ? " AND" : "")." m.id NOT IN (".implode(',', $not_allowed).")";
		
		return '';
	}
	
	function getPriceFormat($price, $currency=null)
	{
		$price = number_format($price, 2, '.', '');
		
		$format    = jtourHelper::getConfig('price_format');
		if (!$currency)
			$currency  = jtourHelper::getConfig('currency');
		$show_free = jtourHelper::getConfig('price_show_free');
		
		if ($show_free && (empty($price) || $price == '0.00'))
			return JText::_('RSM_FREE');
		
		return str_replace(array('{price}', '{currency}'), array($price, $currency), $format);
	}
	
	function genKeyCode()
	{
		$code = jtourHelper::getConfig('global_register_code');
		if ($code === false)
			$code = '';
		return md5($code._JTOUR_KEY);
	}
	
	function createThumb($src, $dest, $thumb_w, $type='jpg')
	{
		jimport('joomla.filesystem.file');
		
		$dest = $dest.'.'.$type;
		
		// load image
		$img = imagecreatefromjpeg($src);
		if ($img)
		{
			// get image size
			$width = imagesx($img);
			$height = imagesy($img);

			// calculate thumbnail size
			$new_width = $thumb_w;
			$new_height = floor($height*($thumb_w/$width));

			// create a new temporary image
			$tmp_img = imagecreatetruecolor($new_width, $new_height);

			// copy and resize old image into new image
			imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			// save thumbnail into a file
			imagejpeg($tmp_img, $dest);
			return true;
		}
		else
			return false;
	}


	
	function sendExpirationEmails()
	{
		jtourHelper::readConfig();
		
		$db = JFactory::getDBO();
		
		// Check the last time this has been run
		$now = jtourHelper::getCurrentDate();
		if ($now < jtourHelper::getConfig('expire_last_run') + jtourHelper::getConfig('expire_check_in')*60)
			return;
		
		$db->setQuery("UPDATE #__jtour_configuration SET `value`='".$now."' WHERE `name`='expire_last_run'");
		$db->query();
		
		// Get custom fields
		$db->setQuery("SELECT id, name FROM #__jtour_fields WHERE published='1' ORDER BY ordering");
		$fields = $db->loadObjectList();
		
		// Get expiration intervals and memberships
		// Performance check - if no emails can be sent, no need to grab the membership
		$db->setQuery("SELECT * FROM #__jtour_memberships WHERE (`user_email_from_addr`!='' OR `user_email_use_global`='1' OR `admin_email_to_addr` != '') AND published='1'");
		$memberships = $db->loadObjectList();
		
		$date = JFactory::getDate();
		$update_ids = array();
		foreach ($memberships as $membership)
		{
			// Select all the subscriptions that match (about to expire)
			$db->setQuery("SELECT u.id AS user_id, u.email AS user_email, u.name AS user_name, u.username AS user_username, mu.id AS muid, mu.extras, mu.membership_end FROM #__jtour_membership_users mu LEFT JOIN #__users u ON (mu.user_id=u.id) WHERE mu.`status`='0' AND mu.`notified`='0' AND mu.membership_end > 0 AND mu.`membership_end` < '".($date->toUnix() + $membership->expire_notify_interval*86400)."' AND mu.`membership_id`='".$membership->id."' LIMIT ".(int) jtourHelper::getConfig('expire_emails'));
			$results = $db->loadObjectList();
			if (empty($results)) continue;
			
			foreach ($results as $result)
			{
				$extras = '';
				// Performance check
				if ($result->extras && (strpos($membership->user_email_expire_text.$membership->user_email_expire_subject, '{extras}') !== false || strpos($membership->admin_email_expire_text.$membership->admin_email_expire_subject, '{extras}') !== false))
				{
					$db->setQuery("SELECT `name` FROM #__jtour_extra_values WHERE `id` IN (".$result->extras.")");
					$extras = implode(', ', $db->loadResultArray());
				}
				
				$replace = array('{membership}', '{extras}', '{email}', '{name}', '{username}', '{interval}');
				$with = array($membership->name, $extras, $result->user_email, $result->user_name, $result->user_username, ceil(($result->membership_end - $date->toUnix())/86400));
				
				$db->setQuery("SELECT * FROM #__jtour_users WHERE user_id='".(int) $result->user_id."'");
				$user_data_tmp = $db->loadObject();
				
				$user_data = array();
				foreach ($fields as $field)
				{
					$field_id = 'f'.$field->id;
					$user_data[$field->name] = isset($user_data_tmp->{$field_id}) ? $user_data_tmp->{$field_id} : '';
				}
				unset($user_data_tmp);
				
				foreach ($fields as $field)
				{
					$name = $field->name;
					$replace[] = '{'.$name.'}';
					if (isset($user_data[$name]))
						$with[] = is_array($user_data[$name]) ? implode("\n", $user_data[$name]) : $user_data[$name];
					else
						$with[] = '';
				}
				
				$jconfig = JFactory::getConfig();
				
				if ($membership->user_email_expire_subject)
				{
					$message = str_replace($replace, $with, $membership->user_email_expire_text);
					// from address
					$from = $membership->user_email_use_global ? $jconfig->get('mailfrom') : $membership->user_email_from_addr;
					// from name
					$fromName = $membership->user_email_use_global ? $jconfig->get('fromname') : $membership->user_email_from;
					// recipient
					$recipient = $result->user_email; // user email
					// subject
					$subject = str_replace($replace, $with, $membership->user_email_expire_subject);
					// body
					$body = $message;
					// mode
					$mode = $membership->user_email_mode; 
					// cc
					$cc = null;
					// bcc
					$bcc = null;
					// attachments
					$db->setQuery("SELECT `path` FROM #__jtour_membership_attachments WHERE `membership_id`='".$membership->id."' AND `email_type`='user_email_expire' AND `published`='1' ORDER BY `ordering`");
					$attachment = $db->loadResultArray();
					// reply to
					$replyto = $from;
					// reply to name
					$replytoname = $fromName;
					// send to user
					
					JUtility::sendMail($from, $fromName, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
				}
				
				// admin emails
				if ($membership->admin_email_expire_subject)
				{
					$message = str_replace($replace, $with, $membership->admin_email_expire_text);
					// from address
					$from = $result->user_email;
					// from name
					$fromName = $result->user_name;
					// recipient
					$recipient = $membership->admin_email_to_addr;
					// subject
					$subject = $membership->admin_email_expire_subject;
					// body
					$body = $message;
					// mode
					$mode = $membership->admin_email_mode;
					// cc
					$cc = null;
					// bcc
					$bcc = null;
					// attachments
					$attachment = null;
					// reply to
					$replyto = $from;
					// reply to name
					$replytoname = $fromName;
					// send to admin
					if ($subject != '')
						JUtility::sendMail($from, $fromName, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
				}
				
				$update_ids[] = $result->muid;
			}
		}
		
		if (!empty($update_ids))
		{
			$db->setQuery("UPDATE #__jtour_membership_users SET `notified`='1' WHERE id IN (".implode(',', $update_ids).")");
			$db->query();
		}
	}
	
	function getUserSubscriptions()
	{
		// Get the logged in user
		$user =& JFactory::getUser();
		
		// Get the database object
		$db =& JFactory::getDBO();
		
		// Get his subscribed memberships
		$memberships = array();
		$extras 	 = array();
		
		if (!$user->get('guest'))
		{
			$db->setQuery("SELECT `membership_id`, `extras` FROM #__jtour_membership_users WHERE `user_id`='".$user->get('id')."' AND `status`='0'");
			$results = $db->loadObjectList();
			
			if ($results)
			foreach ($results as $result)
			{
				$memberships[] = $result->membership_id;
				
				if ($result->extras)
				{
					$extra = explode(',', $result->extras);
					$extras = array_merge($extras, $extra);
				}
			}
		}
		
		return array($memberships, $extras);
	}
	
	function getShared($type=null, $search=null)
	{
		// Get the database object
		$db =& JFactory::getDBO();
		
		$where = '';
		if ($type)
		{
			if (is_array($type))
				$where = " s.`type` IN ('".implode("','", $type)."')";
			else
				$where = " s.`type`='".$db->getEscaped($type)."'";
		}
		if ($search)
			$where .= " AND s.`params` LIKE '".$db->getEscaped($search)."'";
		
		$db->setQuery("SELECT s.`membership_id`, s.`params`, s.`type` FROM #__jtour_membership_shared s LEFT JOIN #__jtour_memberships m ON (s.membership_id=m.id) WHERE $where AND s.`published`='1' AND m.published='1'");
		$shared = $db->loadObjectList();
		
		$db->setQuery("SELECT s.`extra_value_id`, s.`params`, s.`type` FROM #__jtour_extra_value_shared s LEFT JOIN #__jtour_extra_values v ON (s.extra_value_id=v.id) WHERE $where AND s.`published`='1' AND v.published='1'");
		$shared2 = $db->loadObjectList();
		if (!empty($shared2))
			$shared = array_merge($shared, $shared2);
		
		return $shared;
	}
	
	function checkContentJ16(&$has_access, &$found_shared, &$redirect, $memberships, $extras)
	{
		// Get the database object
		$db =& JFactory::getDBO();
		
		$shared = jtourHelper::getShared(array('article', 'category'));
		
		$view = JRequest::getVar('view');
		$id   = JRequest::getInt('id');
		
		if ($view == 'article')
		{
			$categories = jtourHelper::_getItemCategories($id);
			
			foreach ($shared as $share)
			{
				$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__jtour_memberships' : '#__jtour_extra_values';
					
				if (($share->type == 'article' && $share->params == $id) || ($share->type == 'category' && in_array($share->params, $categories)))
				{
					$found_shared = true;
					
					// Found a membership that shares this article
					if (!empty($where) && in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
						$redirect = $db->loadResult();
					}
				}
			}
		}
		elseif ($view == 'category')
		{
			$categories = jtourHelper::_getCategoryParents($id);
			array_push($categories, $id);
			
			foreach ($shared as $share)
			{
				$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__jtour_memberships' : '#__jtour_extra_values';
					
				if ($share->type == 'category' && in_array($share->params, $categories))
				{
					$found_shared = true;
					
					// Found a membership that shares this article
					if (!empty($where) && in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
						$redirect = $db->loadResult();
					}
				}
			}
		}
	}
	
	function _getItemCategories($id)
	{
		// Get the database object
		$db =& JFactory::getDBO();
		
		$db->setQuery("SELECT `catid` FROM #__content WHERE `id`='".(int) $id."'");
		$catid = $db->loadResult();
		$categories = jtourHelper::_getCategoryParents($catid);
		array_push($categories, $catid);
		
		return $categories;
	}
	
	function _getCategoryParents($catid)
	{
		$db =& JFactory::getDBO();
		
		$categories = array();
		$db->setQuery("SELECT `parent_id` FROM #__categories WHERE `extension`='com_content' AND `id`='".(int) $catid."'");
		while ($catid = $db->loadResult())
		{
			if ($catid == 1) break;
			$categories[] = $catid;
			$db->setQuery("SELECT `parent_id` FROM #__categories WHERE `extension`='com_content' AND `id`='".(int) $catid."'");
		}
		
		return $categories;
	}
	
	function checkContent(&$has_access, &$found_shared, &$redirect, $memberships, $extras)
	{
		if (jtourHelper::isJ16())
			return jtourHelper::checkContentJ16($has_access, $found_shared, $redirect, $memberships, $extras);
		
		// Get the database object
		$db =& JFactory::getDBO();
		
		$shared = jtourHelper::getShared(array('article', 'category', 'section'));
		
		$view = JRequest::getVar('view');
		$id   = JRequest::getInt('id');
		
		if ($view == 'article')
		{
			// Must check if it belongs to a category or section that we are sharing
			$db->setQuery("SELECT `sectionid`, `catid` FROM #__content WHERE `id`='".$id."'");
			$content = $db->loadObject();
			
			foreach ($shared as $share)
			{
				$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__jtour_memberships' : '#__jtour_extra_values';
					
				if (($share->type == 'article' && $share->params == $id) || ($share->type == 'category' && $share->params == $content->catid) || ($share->type == 'section' && $share->params == $content->sectionid))
				{
					$found_shared = true;
					
					// Found a membership that shares this article
					if (!empty($where) && in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
						$redirect = $db->loadResult();
					}
				}
			}
		}
		elseif ($view == 'category')
		{
			// Must check if it belongs to a category or section that we are sharing
			$db->setQuery("SELECT `section` FROM #__categories WHERE `id`='".$id."'");
			$sectionid = $db->loadResult();
			
			foreach ($shared as $share)
			{
				$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__jtour_memberships' : '#__jtour_extra_values';
					
				if (($share->type == 'category' && $share->params == $id) || ($share->type == 'section' && $share->params == $sectionid))
				{
					$found_shared = true;
					
					// Found a membership that shares this category
					if (!empty($where) && in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
						$redirect = $db->loadResult();
					}
				}
			}
		}
		elseif ($view == 'section')
		{
			foreach ($shared as $share)
			{
				$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__jtour_memberships' : '#__jtour_extra_values';
					
				if ($share->type == 'section' && $share->params == $id)
				{
					$found_shared = true;
					
					// found a membership that shares this article
					if (!empty($where) && in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
						$redirect = $db->loadResult();
					}
				}
			}
		}
	}
	
	function checkURL(&$has_access, &$found_shared, &$redirect, $memberships, $extras)
	{
		$option    = jtourHelper::getOption();
		$mainframe =& JFactory::getApplication();
		$type      = $mainframe->isAdmin() ? 'backendurl' : 'frontendurl';
		$shared    = jtourHelper::getShared($type, $option.'%');
		
		// Get the database object
		$db =& JFactory::getDBO();
		
		if (!empty($shared))
			foreach ($shared as $share)
			{
				$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__jtour_memberships' : '#__jtour_extra_values';
						
				$query = jtourHelper::parseQuery($share->params);
					
				$current_query = array();
				foreach ($query as $q => $value)
				{
					$var = JRequest::getVar($q, false, 'request', 'none', JREQUEST_ALLOWRAW);
					if ($var !== false)
						$current_query[] = $q.'='.$var;
				}
				$current_query = $option.(!empty($current_query) ? '&'.implode('&', $current_query) : '');
				
				if ($current_query == $share->params || jtourHelper::_is_match($current_query, $share->params))
				{
					$found_shared = true;
					
					if (in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
						$redirect = $db->loadResult();
					}
				}
			}
	}
	
	function checkMenu(&$has_access, &$found_shared, &$redirect, $memberships, $extras)
	{
		$mainframe =& JFactory::getApplication();
		$Itemid    = JRequest::getInt('Itemid');
		$type      = $mainframe->isAdmin() ? 'backendurl' : 'frontendurl';
		$shared    = jtourHelper::getShared('menu', $Itemid);
		
		// Get the database object
		$db =& JFactory::getDBO();
		
		if (!empty($shared))
			foreach ($shared as $share)
			{
				$what  = isset($share->membership_id) ? 'membership_id' : 'extra_value_id';
				$where = isset($share->membership_id) ? $memberships : $extras;
				$table = isset($share->membership_id) ? '#__jtour_memberships' : '#__jtour_extra_values';
						
				if ($share->params == $Itemid)
				{
					$found_shared = true;
					if (in_array($share->{$what}, $where))
					{
						$has_access = true;
						break;
					}
					else
					{
						// Get the redirect page
						$db->setQuery("SELECT `share_redirect` FROM ".$table." WHERE `id`='".$share->{$what}."'");
						$redirect = $db->loadResult();
					}
				}
			}
	}
	

	
	function checkShared()
	{
		$option = jtourHelper::getOption();
		if (!$option)
			return;
		
		$mainframe =& JFactory::getApplication();
		
		// Get the language
		$lang =& JFactory::getLanguage();
		$lang->load('com_jtour');
		$msg = JText::_('RSM_MEMBERSHIP_NEED_SUBSCRIPTION');
		
		// Get the database object
		$db = JFactory::getDBO();

		list($memberships, $extras) = jtourHelper::getUserSubscriptions();
		
		$has_access   = false;
		$found_shared = false;
		$redirect 	  = '';
		
		if (!$mainframe->isAdmin())
		{
			// Check the articles, categories and sections
			if ($option == 'com_content') jtourHelper::checkContent($has_access, $found_shared, $redirect, $memberships, $extras);
			// Menu - Itemid
			jtourHelper::checkMenu($has_access, $found_shared, $redirect, $memberships, $extras);
		}
		
		$instances = jtour::getSharedContentPlugins();
		foreach ($instances as $instance)
			if (method_exists($instance, 'checkShared'))
				$instance->checkShared($option, $has_access, $found_shared, $redirect, $memberships, $extras);
		
		// Custom URL
		jtourHelper::checkURL($has_access, $found_shared, $redirect, $memberships, $extras);
		
		if (!$found_shared)
			$has_access = true;
		
		if (!$has_access)
		{
			$redirect = empty($redirect) ? JURI::root() : $redirect;
			$mainframe->redirect($redirect, $msg);
		}
	}
	
	function _is_match($url, $pattern)
	{
		$pattern = jtourHelper::_transform_string($pattern);	
		preg_match_all($pattern, $url, $matches);
		
		return (!empty($matches[0]));
	}

	function _transform_string($string)
	{
		$string = preg_quote($string, '/');
		$string = str_replace(preg_quote('{*}', '/'), '(.*)', $string);	
		
		$pattern = '#\\\{(\\\\\?){1,}\\\}#';
		preg_match_all($pattern, $string, $matches);
		if (count($matches[0]) > 0)
			foreach ($matches[0] as $match)
			{
				$count = count(explode('\?', $match)) - 1;
				$string = str_replace($match, '(.){'.$count.'}', $string);
			}
		
		return '#'.$string.'#';
	}
	
	function parseQuery($query)
	{
		$return = array();
		
		$query = explode('&', $query);
		unset($query[0]);
		foreach ($query as $q)
		{
			$new = explode('=', $q);
			$return[$new[0]] = @$new[1];
		}
		
		return $return;
	}
	
	function getComponents()
	{
		$query = "SELECT DISTINCT(`option`) FROM #__components WHERE `option`!='' ORDER BY `option` ASC";
		$components = $this->_getList($query);
		$tmps = array('com_admin', 'com_frontpage', 'com_trash', 'com_sections', 'com_categories', 'com_checkin');
		foreach ($tmps as $tmp)
		{
			$new = new stdClass();
			$new->option = $tmp;
			$components[] = $new;
		}
		
		return $components;
	}
	
	function showCustomField($field, $selected=array(), $editable=true, $show_required=true)
	{
		if (empty($field) || empty($field->type)) return false;
		
		$return = array();
		$return[0] = JText::_($field->label);
		$return[1] = '';
		
		switch ($field->type)
		{
			case 'hidden':
				$name = 'rsm_fields['.$field->name.']';
				
				$field->values = jtourHelper::isCode($field->values);
				$return[1] = '<input type="hidden" name="'.$name.'" id="rsm_'.$field->name.'" value="'.htmlspecialchars($field->values, ENT_COMPAT, 'utf-8').'" />';
			break;
			
			case 'freetext':
				$field->values = jtourHelper::isCode($field->values);
				$return[1] = $field->values;
			break;
			
			case 'textbox':
				if (isset($selected[$field->name]))
					$field->values = $selected[$field->name];
				else
					$field->values = jtourHelper::isCode($field->values);
				
				$name = 'rsm_fields['.$field->name.']';
				
				$return[1] = '<input type="text" class="rsm_textbox" name="'.$name.'" id="rsm_'.$field->name.'" value="'.htmlspecialchars($field->values, ENT_COMPAT, 'utf-8').'" size="40" '.$field->additional.' />';
				
				if (!$editable)
					$return[1] = htmlspecialchars($field->values, ENT_COMPAT, 'utf-8');
			break;
			
			case 'textarea':
				if (isset($selected[$field->name]))
					$field->values = $selected[$field->name];
				else
					$field->values = jtourHelper::isCode($field->values);
				
				$name = 'rsm_fields['.$field->name.']';
				
				$return[1] = '<textarea class="textarea rsm_textarea" name="'.$name.'" id="rsm_'.$field->name.'" '.$field->additional.'>'.htmlspecialchars($field->values, ENT_COMPAT, 'utf-8').'</textarea>';
				
				if (!$editable)
					$return[1] = nl2br(htmlspecialchars($field->values, ENT_COMPAT, 'utf-8'));
			break;
			
			case 'select':
			case 'multipleselect':
				$field->values = jtourHelper::isCode($field->values);
				$field->values = str_replace("\r\n", "\n", $field->values);
				$field->values = explode("\n", $field->values);
				
				$multiple = $field->type == 'multipleselect' ? 'multiple="multiple"' : '';
				
				$name = 'rsm_fields['.$field->name.'][]';
				
				$return[1] = '<select '.$multiple.' class="rsm_select" name="'.$name.'" id="rsm_'.$field->name.'" '.$field->additional.'>';
					foreach ($field->values as $value)
					{
						$found_checked = false;
						if (preg_match('/\[c\]/',$value))
						{
							$value = str_replace('[c]', '', $value);
							$found_checked = true;
						}
						
						$checked = '';
						if (isset($selected[$field->name]) && in_array($value, $selected[$field->name]))
							$checked = 'selected="selected"';
						elseif (!isset($selected[$field->name]) && $found_checked)
							$checked = 'selected="selected"';
						
						$return[1] .= '<option '.$checked.' value="'.htmlspecialchars($value, ENT_COMPAT, 'utf-8').'">'.htmlspecialchars($value, ENT_COMPAT, 'utf-8').'</option>';
					}
				$return[1] .= '</select>';
				
				if (!$editable)
				{
					$return[1] = '';
					if (isset($selected[$field->name]))
					{
						if (is_array($selected[$field->name]))
							$return[1] = nl2br(htmlspecialchars(implode("\n", $selected[$field->name]), ENT_COMPAT, 'utf-8'));
						else
							$return[1] = htmlspecialchars($selected[$field->name], ENT_COMPAT, 'utf-8');
					}
				}
			break;
			
			case 'checkbox':
				$field->values = jtourHelper::isCode($field->values);
				$field->values = str_replace("\r\n", "\n", $field->values);
				$field->values = explode("\n", $field->values);
				
				foreach ($field->values as $i => $value)
				{
					$found_checked = false;
					if (preg_match('/\[c\]/',$value))
					{
						$value = str_replace('[c]', '', $value);
						$found_checked = true;
					}
					
					$checked = '';
					if (isset($selected[$field->name]) && in_array($value, $selected[$field->name]))
						$checked = 'checked="checked"';
					elseif (!isset($selected[$field->name]) && $found_checked)
						$checked = 'checked="checked"';
					
					$name = 'rsm_fields['.$field->name.'][]';
					
					$return[1] .= '<input '.$checked.' type="checkbox" name="'.$name.'" value="'.htmlspecialchars($value, ENT_COMPAT, 'utf-8').'" id="rsm_field_'.$field->id.'_'.$i.'" '.$field->additional.' /> <label for="rsm_field_'.$field->id.'_'.$i.'">'.$value.'</label>';
				}
				
				if (!$editable)
				{
					$return[1] = '';
					if (isset($selected[$field->name]))
					{
						if (is_array($selected[$field->name]))
							$return[1] = nl2br(htmlspecialchars(implode("\n", $selected[$field->name]), ENT_COMPAT, 'utf-8'));
						else
							$return[1] = htmlspecialchars($selected[$field->name], ENT_COMPAT, 'utf-8');
					}
				}
			break;
			
			case 'radio':
				$field->values = jtourHelper::isCode($field->values);
				$field->values = str_replace("\r\n", "\n", $field->values);
				$field->values = explode("\n", $field->values);
				
				foreach ($field->values as $i => $value)
				{
					$found_checked = false;
					if (preg_match('/\[c\]/',$value))
					{
						$value = str_replace('[c]', '', $value);
						$found_checked = true;
					}
					
					$checked = '';
					if (isset($selected[$field->name]) && $selected[$field->name] == $value)
						$checked = 'checked="checked"';
					elseif (!isset($selected[$field->name]) && $found_checked)
						$checked = 'checked="checked"';
					
					$name = 'rsm_fields['.$field->name.']';
					
					$return[1] .= '<input '.$checked.' type="radio" name="'.$name.'" value="'.htmlspecialchars($value, ENT_COMPAT, 'utf-8').'" id="rsm_field_'.$field->id.'_'.$i.'" '.$field->additional.' /> <label for="rsm_field_'.$field->id.'_'.$i.'">'.$value.'</label>';
				}
				
				if (!$editable)
					$return[1] = htmlspecialchars(@$selected[$field->name], ENT_COMPAT, 'utf-8');
			break;
			
			case 'calendar':
				if (isset($selected[$field->name]))
					$field->values = $selected[$field->name];
				else
					$field->values = jtourHelper::isCode($field->values);
					
				$name = 'rsm_fields['.$field->name.']';
				
				$format = jtourHelper::getConfig('date_format');
				$format = jtourHelper::getCalendarFormat($format);
				
				if ($editable)
					$return[1] = JHTML::_('calendar', $field->values, $name, 'rsm_'.$field->name, $format, $field->additional); 
				else
					$return[1] = htmlspecialchars($field->values, ENT_COMPAT, 'utf-8');
			break;
		}
		
		if ($field->required && $editable && $show_required)
			$return[1] .= ' '.JText::_('RSM_REQUIRED');
		
		return $return;
	}
	
	function isCode($value)
	{
		if (preg_match('/\/\/<code>/',$value))
			return eval($value);
		else
			return $value;
	}
	
	function getCalendarFormat($format)
	{
		$php = array( '%',  'D',  'l',  'M',  'F',  'd',  'j',  'H',  'h',  'z',  'G',  'g',  'm',  'i', "\n",  'A',  'a',  's',  'U', "\t",  'W',  'N',  'w',  'y',  'Y');
		$js  = array('%%', '%a', '%A', '%b', '%B', '%d', '%e', '%H', '%I', '%j', '%k', '%l', '%m', '%M', '%n', '%p', '%P', '%S', '%s', '%t', '%U', '%u', '%w', '%y', '%Y');
		
		return str_replace($php, $js, $format);
	}
	
	function getUserFields($user_id)
	{
		$return = array();
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__jtour_users WHERE user_id='".(int) $user_id."'");
		$result = $db->loadObject();
		
		$db->setQuery("SELECT id, name FROM #__jtour_fields WHERE published='1' ORDER BY ordering");
		$fields = $db->loadObjectList();
		foreach ($fields as $field)
		{
			$field_id = 'f'.$field->id;
			$return[$field->name] = $result->{$field_id};
		}
		
		return $return;
	}
	
	function getFields($editable=true, $user_id=false, $show_required=true)
	{
		$return = array();
		
		$db = JFactory::getDBO();
		if ($user_id)
		{
			$user = JFactory::getUser($user_id);
			$guest = false;
		}
		else
		{
			$user = JFactory::getUser();
			$guest = $user->get('guest');
		}
			
		$post = JRequest::getVar('rsm_fields', array(), 'post');
		
		$db->setQuery("SELECT * FROM #__jtour_fields WHERE published='1' ORDER BY ordering");
		$fields = $db->loadObjectList();
		
		if (!$post && !$guest)
		{
			$db->setQuery("SELECT * FROM #__jtour_users WHERE user_id='".$user->get('id')."'");
			$data = $db->loadObject();
			
			if ($data)
			{
				unset($data->user_id);
				
				foreach ($fields as $field)
				{
					$field_id = 'f'.$field->id;
					if (!isset($data->$field_id))
						continue;
						
					if (in_array($field->type, array('select', 'multipleselect', 'checkbox')))
						$post[$field->name] = explode("\n", $data->$field_id);
					else
						$post[$field->name] = $data->$field_id;
				}
			}
		}
		
		foreach ($fields as $field)
			$return[] = jtourHelper::showCustomField($field, $post, $editable, $show_required);		
		
		return $return;
	}
	
	function getFieldsValidation()
	{
		$return = array();
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__jtour_fields WHERE published='1' AND required = '1' ORDER BY ordering");
		$fields = $db->loadObjectList();
		foreach ($fields as $field)
		{
			$js = '';
			
			switch ($field->type)
			{
				case 'select':
				case 'multipleselect':
				case 'textarea':
				case 'textbox':
				case 'calendar':
					$element = 'rsm_'.$field->name;
					$js .= "if (document.getElementById('".$element."').value.length == 0)"."\n";
				break;
				
				case 'checkbox':
				case 'radio':
					$field->values = jtourHelper::isCode($field->values);
					$field->values = str_replace("\r\n", "\n", $field->values);
					$field->values = explode("\n", $field->values);

					$ids = array();
					foreach ($field->values as $i => $value)
					{
						$element = 'rsm_field_'.$field->id.'_'.$i;
						$ids[] = "!document.getElementById('".$element."').checked";
					}
					
					$element = '';
					
					$js .= "if (".implode(" && ", $ids).")"."\n";
				break;
			}
			
			$validation_message = JText::_($field->validation);
			if (empty($validation_message))
				$validation_message = JText::sprintf('RSM_VALIDATION_DEFAULT_ERROR', JText::_($field->label));
			$js .= "{\n";
			$js .= "msg.push('".JText::_($validation_message, true)."');"."\n";
			if (@$element)
				$js .= "document.getElementById('".$element."').className += ' rsm_field_error';\n";
			$js .= "}\n";
			
			$return[] = $js;
		}
		
		return $return;
	}
	
	function calculateFixedDate($membership)
	{
		$date  = JFactory::getDate();
		$now   = jtourHelper::getCurrentDate();
		$day   = $membership->fixed_day;
		$month = $membership->fixed_month;
		$year  = $membership->fixed_year;
		
		if ($day == 0 && $month == 0 && $year == 0)
		{
			// Add a day
			$membership_end = $date->toUnix() + 86400;
		}
		elseif ($day > 0 && $month == 0 && $year == 0)
		{
			// If we didn't pass the expiry day, expire this month, else expire next month
			$month = date('d', $now) < $day ? date('m', $now) : date('m', $now) + 1;
			$year  = date('Y', $now);
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		}
		elseif ($day == 0 && $month > 0 && $year == 0)
		{
			$day  = date('m', $now) == $month ? date('d', $now) + 1 : 1;
			$year = date('Y', $now);
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		}
		elseif ($day == 0 && $month == 0 && $year >  0)
		{
			$month = date('m', $now);
			$day   = date('d', $now) + 1;
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		}
		elseif ($day >  0 && $month >  0 && $year == 0)
		{
			// If we didn't pass the expiry day and month, expire this year, else expire next year
			$year = date('d', $now) < $day && date('m', $now) == $month || date('m', $now) < $month ? date('Y', $now) : date('Y', $now) + 1;
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		}
		elseif ($day == 0 && $month >  0 && $year >  0)
		{
			$day  = date('m', $now) == $month && date('Y', $now) == $year ? date('d', $now) + 1 : 1;
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		}
		elseif ($day >  0 && $month == 0 && $year >  0)
		{
			// If we didn't pass the expiry day, expire this month, else expire next month
			$month = date('d', $now) < $day ? date('m', $now) : date('m', $now) + 1;
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		}
		elseif ($day >  0 && $month >  0 && $year >  0)
		{
			// Expire on a fixed date
			$membership_end = gmmktime(0, 0, 0, $month, $day, $year);
		}
		
		return $membership_end;
	}
}

class JTour
{
	var $_plugins = array();
	
	function getSharedContentPlugins()
	{
		jimport('joomla.plugin.helper');
		
		static $instances;
		if (!is_array($instances))
		{
			$instances = array();
			$dispatcher =& JDispatcher::getInstance();
		
			// Get plugins		
			JPluginHelper::importPlugin('jtour');
			$plugins = JPluginHelper::getPlugin('jtour');
			foreach($plugins as $plugin)
			{
				JPluginHelper::importPlugin('jtour', $plugin->name, false);

				$className = 'plgjtour'.$plugin->name;
				if(class_exists($className))
					$instances[] = new $className($dispatcher, (array)$plugin);
			}
		}
		
		return $instances;
	}
	
	function saveTransactionLog($log, $id, $append=true)
	{
		if (!$log || !$id)
			return false;
			
		$db =& JFactory::getDBO();
		
		if (!is_array($log))
			$log = array($log);
		
		foreach ($log as $i => $item)
			$log[$i] = JHTML::date('now').' '.$item;
		
		$log = implode("\n", $log);
		
		if ($append)
			$db->setQuery("UPDATE #__jtour_transactions SET `response_log` = CONCAT(`response_log`,'".$db->getEscaped("\n".$log)."') WHERE `id`='".(int) $id."'");
		else
			$db->setQuery("UPDATE #__jtour_transactions SET `response_log`='".$db->getEscaped($log)."' WHERE `id`='".(int) $id."'");
			
		return $db->query();
	}
	
	function jtour()
	{
		return true;
	}
	
	function addPlugin($name, $filename)
	{
		$instance =& jtour::getInstance();
		
		$instance->_plugins[$filename] = $name;
	}
	
	function getPlugins()
	{
		$instance =& jtour::getInstance();
		
		return $instance->_plugins;
	}
	
	function getPlugin($name)
	{
		$instance =& jtour::getInstance();
		
		if (!empty($instance->_plugins[$name]))
			return $instance->_plugins[$name];
		else
			return false;
	}
	
	function processPluginResult($array)
	{
		if (is_array($array))
		{
			foreach ($array as $item)
				if ($item !== false)
					return $item;
		}
		else
			return $array;
	}
	
	function &getInstance()
	{
		static $instance;

		if (!is_object($instance))
			$instance = new jtour();

		return $instance;
	}
	
	function finalize($transaction_id)
	{
		$mainframe =& JFactory::getApplication();
		$option = 'com_jtour';
		$db = JFactory::getDBO();
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jtour'.DS.'tables');
		
		// get transaction details
		$transaction =& JTable::getInstance('jtour_Transactions','Table');
		$transaction->load($transaction_id);
		
		if (!$transaction->params)
			return false;
		
		// get user details
		$user_data = unserialize($transaction->user_data);
		$user_email = $transaction->user_email;
		
		// get membership details
		$params = jtourHelper::parseParams($transaction->params);
		
		$membership =& JTable::getInstance('jtour_Memberships','Table');
		$extras = '';
		
		switch ($transaction->type)
		{
			case 'new':
				$transaction->membership_id = $params['membership_id'];
				$membership->load($transaction->membership_id);
				
				$message = $membership->user_email_new_text;
				$subject = $membership->user_email_new_subject;
				
				$admin_message = $membership->admin_email_new_text;
				$admin_subject = $membership->admin_email_new_subject;
				
				$email_type = 'user_email_new';
			break;
			
			case 'upgrade':
				$transaction->membership_id = $params['to_id'];
				$membership->load($transaction->membership_id);
				
				$message = $membership->user_email_upgrade_text;
				$subject = $membership->user_email_upgrade_subject;
				
				$admin_message = $membership->admin_email_upgrade_text;
				$admin_subject = $membership->admin_email_upgrade_subject;
				
				$email_type = 'user_email_upgrade';
			break;
			
			case 'addextra':
				$transaction->membership_id = $params['membership_id'];
				$membership->load($transaction->membership_id);
				
				$message = $membership->user_email_addextra_text;
				$subject = $membership->user_email_addextra_subject;
				
				$admin_message = $membership->admin_email_addextra_text;
				$admin_subject = $membership->admin_email_addextra_subject;
				
				$email_type = 'user_email_addextra';
			break;
			
			case 'renew':
				$transaction->membership_id = $params['membership_id'];
				$membership->load($transaction->membership_id);
				
				$message = $membership->user_email_renew_text;
				$subject = $membership->user_email_renew_subject;
				
				$admin_message = $membership->admin_email_renew_text;
				$admin_subject = $membership->admin_email_renew_subject;
				
				$email_type = 'user_email_renew';
			break;
		}
		
		if (!empty($params['extras']))
		{
			$db->setQuery("SELECT `name` FROM #__jtour_extra_values WHERE `id` IN (".implode(',', $params['extras']).")");
			$extras = implode(', ', $db->loadResultArray());
		}
		
		$replace = array('{membership}', '{extras}', '{email}', '{name}', '{username}', '{continue}', '{price}');
		$with = array($membership->name, $extras, $user_email, $user_data->name, (isset($user_data->username) ? $user_data->username : ''),'<input class="button" type="button" onclick="location.href=\''.(!empty($membership->redirect) ? $membership->redirect : JRoute::_('index.php?option=com_jtour')).'\'" value="'.JText::_('RSM_CONTINUE').'" />', jtourHelper::getPriceFormat($transaction->price));
		
		$db->setQuery("SELECT * FROM #__jtour_fields WHERE published='1'");
		$fields = $db->loadObjectList();
		foreach ($fields as $field)
		{
			$name = $field->name;
			$replace[] = '{'.$name.'}';
			if (isset($user_data->fields[$name]))
				$with[] = is_array($user_data->fields[$name]) ? implode("\n", $user_data->fields[$name]) : $user_data->fields[$name];
			else
				$with[] = '';
		}
		
		$jconfig = JFactory::getConfig();
		$membership->user_email_from_addr = $membership->user_email_use_global ? $jconfig->get('mailfrom') : $membership->user_email_from_addr;
		$membership->user_email_from 	  = $membership->user_email_use_global ? $jconfig->get('fromname') : $membership->user_email_from;
		
		// start sending emails
		// user emails
		if (!empty($membership->user_email_from_addr))
		{
			$message = str_replace($replace, $with, $message);
			// from address
			$from = $membership->user_email_from_addr;
			// from name
			$fromName = $membership->user_email_from;
			// recipient
			$recipient = $user_email; // user email
			// subject
			$subject = str_replace($replace, $with, $subject);
			// body
			$body = $message;
			// mode
			$mode = $membership->user_email_mode; 
			// cc
			$cc = null;
			// bcc
			$bcc = null;
			// attachments
			$db->setQuery("SELECT `path` FROM #__jtour_membership_attachments WHERE `membership_id`='".$transaction->membership_id."' AND `email_type`='".$email_type."' AND `published`='1' ORDER BY `ordering`");
			$attachment = $db->loadResultArray();
			// reply to
			$replyto = $from;
			// reply to name
			$replytoname = $fromName;
			// send to user
			if ($subject != '')
				JUtility::sendMail($from, $fromName, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
		}
		
		// admin emails
		if (!empty($membership->admin_email_to_addr) && !empty($admin_subject))
		{
			$message =& $admin_message;
			$message = str_replace($replace, $with, $message);
			// from address
			$from = $user_email;
			// from name
			$fromName = $user_data->name;
			// recipient
			$recipient = $membership->admin_email_to_addr;
			// subject
			$subject = $admin_subject;
			// body
			$body = $message;
			// mode
			$mode = $membership->admin_email_mode;
			// cc
			$cc = null;
			// bcc
			$bcc = null;
			// attachments
			$attachment = null;
			// reply to
			$replyto = $from;
			// reply to name
			$replytoname = $fromName;
			// send to admin
			if ($subject != '')
				JUtility::sendMail($from, $fromName, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
		}
		
		// run php code
		eval($membership->custom_code);
		
		$session =& JFactory::getSession();
		// set the action
		$session->set($option.'.subscribe.action', $membership->action);
		
		// show thank you message
		$thankyou = str_replace($replace, $with, $membership->thankyou);
		$session->set($option.'.subscribe.thankyou', $thankyou);
		
		// show url
		$redirect = str_replace($replace, $with, $membership->redirect);
		$session->set($option.'.subscribe.redirect', $redirect);
	}
	
	function approve($transaction_id, $force=false)
	{
		$db =& JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__jtour_transactions WHERE `id`='".(int) $transaction_id."'".($force ? "" : " AND `status`='pending'"));
		$transaction = $db->loadObject();
		if (empty($transaction->id)) return false;
		
		$params = jtourHelper::parseParams($transaction->params);
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_jtour'.DS.'tables');
		
		$row =& JTable::getInstance('jtour_Membership_Users','Table');
		$row->user_id = $transaction->user_id;
		if (!jtourHelper::getConfig('create_user_instantly') || ($row->user_id == 0))
		{
			$row->user_id = jtour::createUser($transaction->user_email, unserialize($transaction->user_data));
			$db->setQuery("UPDATE #__jtour_transactions SET `user_id`='".$row->user_id."' WHERE `id`='".$transaction->id."'");
			$db->query();
		}
		$row->price = $transaction->price;
		$row->currency = $transaction->currency;
		
		$idev_enabled 		 = jtourHelper::getConfig('idev_enable');
		$idev_track_renewals = jtourHelper::getConfig('idev_track_renewals');
		$update_gid    = false;
		$update_user   = false;
		$update_idev   = false;
		$update_rsmail = false;
		$date = JFactory::getDate();
		switch ($transaction->type)
		{
			case 'new':
				$row->membership_id = $params['membership_id'];
				$db->setQuery("SELECT * FROM #__jtour_memberships WHERE `id`='".(int) $row->membership_id."'");
				$membership = $db->loadObject();
				
				if (empty($membership))
				{
					JError::raiseWarning(500, JText::_('RSM_COULD_NOT_APPROVE_TRANSACTION'));
					return false;
				}
				
				if ($membership->gid_enable)
					$update_gid = true;
				if ($membership->disable_expired_account)
					$update_user = true;
				
				$row->membership_start = $date->toUnix();
				
				if ($membership->use_trial_period)
				{
					$membership->period = $membership->trial_period;
					$membership->period_type = $membership->trial_period_type;
				}
				
				if ($membership->fixed_expiry)
				{
					$row->membership_end = jtourHelper::calculateFixedDate($membership);
				}
				else
				{
					if ($membership->period > 0)
					{
						switch ($membership->period_type)
						{
							case 'h': $offset = $membership->period * 3600; break;
							case 'd': $offset = $membership->period * 86400; break;
							case 'm': $offset = strtotime('+'.$membership->period.' months', $row->membership_start) - $row->membership_start; break;
							case 'y': $offset = strtotime('+'.$membership->period.' years', $row->membership_start) - $row->membership_start; break;
						}
						$row->membership_end = $date->toUnix() + $offset;
					}
					else
						$row->membership_end = 0;
				}
				
				if (!empty($params['extras']))
					$row->extras = implode(',', $params['extras']);
				$row->status = 0;
				
				$row->from_transaction_id = $transaction->id;
				$row->last_transaction_id = $transaction->id;
				
				$row->store();
				$return = $row->id;
				
				// iDev Integration
				if ($idev_enabled)
					$update_idev = true;
				
				$update_rsmail = $membership->id;
			break;
			
			case 'addextra':
				$db->setQuery("SELECT `extras` FROM #__jtour_membership_users WHERE `id`='".(int) $params['id']."'");
				$extras = $db->loadResult();
				$extras = explode(',', $extras);
				if (empty($extras[0]))
					$extras = $params['extras'];
				else
					$extras = array_merge($extras, $params['extras']);
				$db->setQuery("UPDATE #__jtour_membership_users SET `extras`='".implode(',', $extras)."' WHERE `id`='".(int) $params['id']."'");
				$db->query();
				
				$return = $params['id'];
				return $return;
			break;
			
			case 'upgrade':
				// Get the upgraded membership
				$db->setQuery("SELECT * FROM #__jtour_memberships WHERE `id`='".(int) $params['to_id']."'");
				$membership = $db->loadObject();
				
				// Get the current membership
				$db->setQuery("SELECT * FROM #__jtour_membership_users WHERE `id`='".(int) $params['id']."'");
				$current = $db->loadObject();
				$db->setQuery("SELECT * FROM #__jtour_memberships WHERE `id`='".$current->membership_id."'");				
				$old_membership = $db->loadObject();
				
				$db->setQuery("UPDATE #__jtour_membership_users SET `membership_id`='".(int) $params['to_id']."' WHERE `id`='".(int) $params['id']."'");
				$db->query();
				
				$new_price = '';
				$db->setQuery("SELECT price FROM #__jtour_membership_upgrades WHERE `membership_from_id`='".(int) $old_membership->id."' AND `membership_to_id`='".(int) $membership->id."' AND `published`='1'");
				$upgrade = $db->loadResult();				
				if ($upgrade)
					$new_price = ", `price`='".$db->getEscaped($current->price + $upgrade)."'";
				
				if ($membership->fixed_expiry)
				{
					$membership_end = jtourHelper::calculateFixedDate($membership);
					$status = '';
					if ($membership_end > $date->toUnix())
						$status = ", `status`='0', `notified`='0'";
						
					$db->setQuery("UPDATE #__jtour_membership_users SET membership_end = '".$membership_end."' $status $new_price WHERE `id`='".(int) $params['id']."'");
					$db->query();
				}
				else
				{
					if ($membership->period == 0)
					{
						$db->setQuery("UPDATE #__jtour_membership_users SET `membership_end`='0', `status`='0', `notified`='0' $new_price WHERE `id`='".(int) $params['id']."'");
						$db->query();
					}
					elseif ($membership->period > 0)
					{
						switch ($membership->period_type)
						{
							case 'h': $offset = $membership->period * 3600; break;
							case 'd': $offset = $membership->period * 86400; break;
							case 'm': $offset = strtotime('+'.$membership->period.' months', $current->membership_start) - $current->membership_start; break;
							case 'y': $offset = strtotime('+'.$membership->period.' years', $current->membership_start) - $current->membership_start; break;
						}
						$membership_end = $current->membership_start + $offset;
						$status = '';
						if ($membership_end > $date->toUnix())
							$status = ", `status`='0', `notified`='0'";
							
						$db->setQuery("UPDATE #__jtour_membership_users SET membership_end = '".$membership_end."' $status $new_price WHERE `id`='".(int) $params['id']."'");
						$db->query();
					}
				}
				
				// the last transaction
				$db->setQuery("UPDATE #__jtour_membership_users SET `last_transaction_id`='".$transaction->id."' WHERE `id`='".(int) $params['id']."'");
				$db->query();
				
				if ($membership->gid_enable)
					$update_gid = true;
				
				if ($membership->disable_expired_account)
					$update_user = true;
				
				$update_rsmail = $membership->id;
				
				$return = $params['id'];
			break;
			
			case 'renew':
				$row->membership_id = $params['membership_id'];
				$db->setQuery("SELECT * FROM #__jtour_memberships WHERE `id`='".(int) $row->membership_id."'");
				$membership = $db->loadObject();
				
				$membership_start = $date->toUnix();
				if ($membership->fixed_expiry)
				{
					$membership_end = jtourHelper::calculateFixedDate($membership);
				}
				else
				{
					// Renew when not expired ?
					$db->setQuery("SELECT * FROM #__jtour_membership_users WHERE `id`='".(int) $params['id']."'");
					$current = $db->loadObject();
					if ($current->status == 0)
						$membership_start = $current->membership_end;
						
					if ($membership->period > 0)
					{
						switch ($membership->period_type)
						{
							case 'h': $offset = $membership->period * 3600; break;
							case 'd': $offset = $membership->period * 86400; break;
							case 'm': $offset = strtotime('+'.$membership->period.' months', $membership_start) - $membership_start; break;
							case 'y': $offset = strtotime('+'.$membership->period.' years', $membership_start) - $membership_start; break;
						}
						$membership_end = $membership_start + $offset;
					}
					else
						$membership_end = 0;
					
					if ($current->status == 0)
						$membership_start = $current->membership_start;
				}
				
				$db->setQuery("UPDATE #__jtour_membership_users SET `membership_start`='".$membership_start."', `membership_end`='".$membership_end."',`status`='0', `notified`='0' WHERE `id`='".(int) $params['id']."'");
				$db->query();
				
				// the last transaction
				$db->setQuery("UPDATE #__jtour_membership_users SET `last_transaction_id`='".$transaction->id."' WHERE `id`='".(int) $params['id']."'");
				$db->query();
				
				if ($membership->gid_enable)
					$update_gid = true;
				if ($membership->disable_expired_account)
					$update_user = true;
				
				$return = $params['id'];
				
				// iDev Integration
				if ($idev_enabled && $idev_track_renewals)
					$update_idev = true;
			break;
		}
		
		if ($update_gid)
			jtour::updateGid($row->user_id, $membership->gid_subscribe, true);
		
		if ($update_user)
			jtour::enableUser($row->user_id);
			
		$db->setQuery("UPDATE #__jtour_transactions SET `status`='completed' WHERE `id`='".$transaction->id."'");
		$db->query();
		
		$user_data = unserialize($transaction->user_data);
		$user_email = $transaction->user_email;
		$replace = array('{membership}', '{email}', '{username}', '{name}');
		$with = array($membership->name, $user_email, (isset($user_data->username) ? $user_data->username : ''), $user_data->name);
		
		$db->setQuery("SELECT * FROM #__jtour_fields WHERE published='1'");
		$fields = $db->loadObjectList();
		foreach ($fields as $field)
		{
			$name = $field->name;
			$replace[] = '{'.$name.'}';
			if (isset($user_data->fields[$name]))
				$with[] = is_array($user_data->fields[$name]) ? implode("\n", $user_data->fields[$name]) : $user_data->fields[$name];
			else
				$with[] = '';
		}
		
		if ($update_rsmail)
			jtour::addToRSMail($update_rsmail, $row->user_id, $user_email, $user_data);
		
		$userEmail  = array('from' => '', 'fromName' => '', 'recipient' => '', 'subject' => '', 'body' => '', 'mode' => '', 'cc' => '', 'bcc' => '', 'attachments' => '', 'replyto' => '', 'replytoname' => '');
		$adminEmail = array('from' => '', 'fromName' => '', 'recipient' => '', 'subject' => '', 'body' => '', 'mode' => '', 'cc' => '', 'bcc' => '', 'attachments' => '', 'replyto' => '', 'replytoname' => '');
		
		$jconfig = JFactory::getConfig();
		$membership->user_email_from_addr = $membership->user_email_use_global ? $jconfig->get('mailfrom') : $membership->user_email_from_addr;
		$membership->user_email_from	  = $membership->user_email_use_global ? $jconfig->get('fromname') : $membership->user_email_from;
		
		if (!empty($membership->user_email_from_addr) && $membership->user_email_approved_subject != '')
		{
			// start sending emails
			// from address
			$userEmail['from'] = $membership->user_email_from_addr;
			// from name
			$userEmail['fromName'] = $membership->user_email_from;
			// recipient
			$userEmail['recipient'] = $user_email; // user email
			// subject
			$userEmail['subject'] = str_replace($replace, $with, $membership->user_email_approved_subject);
			// body
			$userEmail['body'] = str_replace($replace, $with, $membership->user_email_approved_text);
			// mode
			$userEmail['mode'] = $membership->user_email_mode; 
			// cc
			$userEmail['cc'] = null;
			// bcc
			$userEmail['bcc'] = null;
			// attachments
			$db->setQuery("SELECT `path` FROM #__jtour_membership_attachments WHERE `membership_id`='".$membership->id."' AND `email_type`='user_email_approved' AND `published`='1' ORDER BY `ordering`");
			$userEmail['attachments'] = $db->loadResultArray();
			// reply to
			$userEmail['replyto'] = $userEmail['from'];
			// reply to name
			$userEmail['replytoname'] = $userEmail['fromName'];
		}
		
		// admin emails
		if (!empty($membership->admin_email_to_addr) && $membership->admin_email_approved_subject != '')
		{
			// from address
			$adminEmail['from'] = $user_email;
			// from name
			$adminEmail['fromName'] = $user_data->name;
			// recipient
			$adminEmail['recipient'] = $membership->admin_email_to_addr;
			// subject
			$adminEmail['subject'] = str_replace($replace, $with, $membership->admin_email_approved_subject);
			// body
			$adminEmail['body'] = str_replace($replace, $with, $membership->admin_email_approved_text);
			// mode
			$adminEmail['mode'] = $membership->admin_email_mode;
			// cc
			$adminEmail['cc'] = null;
			// bcc
			$adminEmail['bcc'] = null;
			// attachments
			$adminEmail['attachments'] = null;
			// reply to
			$adminEmail['replyto'] = $adminEmail['from'];
			// reply to name
			$adminEmail['replytoname'] = $adminEmail['fromName'];
		}
		
		// run php code
		eval($membership->custom_code_transaction);
		
		// send to user
		if (!empty($membership->user_email_from_addr) && $membership->user_email_approved_subject != '')
			JUtility::sendMail($userEmail['from'], $userEmail['fromName'], $userEmail['recipient'], $userEmail['subject'], $userEmail['body'], $userEmail['mode'], $userEmail['cc'], $userEmail['bcc'], $userEmail['attachments'], $userEmail['replyto'], $userEmail['replytoname']);
		
		// send to admin
		if (!empty($membership->admin_email_to_addr) && !empty($membership->admin_email_approved_subject))
			JUtility::sendMail($adminEmail['from'], $adminEmail['fromName'], $adminEmail['recipient'], $adminEmail['subject'], $adminEmail['body'], $adminEmail['mode'], $adminEmail['cc'], $adminEmail['bcc'], $adminEmail['attachments'], $adminEmail['replyto'], $adminEmail['replytoname']);
		
		// process stock
		if ($membership->stock > 0)
		{
			// decrease stock
			if ($membership->stock > 1)
				$this->_db->setQuery("UPDATE #__jtour_memberships SET `stock`=`stock`-1 WHERE `id`='".$membership->id."'");
			// or set it to unavailable (-1 instead of 0, which actually means unlimited)
			else
				$this->_db->setQuery("UPDATE #__jtour_memberships SET `stock`='-1' WHERE `id`='".$membership->id."'");
			$this->_db->query();
		}
		
		if ($update_idev)
			jtour::updateIdev(array('idev_saleamt' => $transaction->price, 'idev_ordernum' => $transaction->id, 'ip_address' => $transaction->ip));
		
		// should return the newly created/updated membership id
		return $return;
	}
	
	function deny($transaction_id, $force=false)
	{
		$db =& JFactory::getDBO();
		$db->setQuery("UPDATE #__jtour_transactions SET `status`='denied' WHERE `id`='".(int) $transaction_id."'".($force ? "" : " AND `status`='pending'"));
		return $db->query();
	}
	
	function createUser($email, $data)
	{
		if (empty($email)) return false;
		
		$email = strtolower($email);
		
		$lang =& JFactory::getLanguage();
		$lang->load('com_user', JPATH_SITE, null, true);
		$lang->load('com_user', JPATH_ADMINISTRATOR, null, true);
		$lang->load('com_users', JPATH_ADMINISTRATOR, null, true);
		$lang->load('com_jtour', JPATH_SITE);
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT `id` FROM #__users WHERE `email` LIKE '".$db->getEscaped($email)."' LIMIT 1");
		if ($user_id = $db->loadResult())
			return $user_id;
			
		jimport('joomla.user.helper');
		// Get required system objects
		$user = clone(JFactory::getUser(0));
		
		@list($username, $domain) = explode('@', $email);
		
		if (jtourHelper::getConfig('choose_username') && !empty($data->username))
			$username = $data->username;
		
		$db->setQuery("SELECT `id` FROM #__users WHERE `username` LIKE '".$db->getEscaped($username)."' LIMIT 1");
		if (preg_match( "#[<>\"'%;()&]#i", $username) || strlen(utf8_decode($username )) < 2)
		{
			$username = JFilterOutput::stringURLSafe($data->name);
			if (strlen($username) < 2)
				$username = str_pad($username, 2, mt_rand(0,9));
		}
		
		while ($db->loadResult())
		{
			$username .= mt_rand(0,9);
			$db->setQuery("SELECT `id` FROM #__users WHERE `username` LIKE '".$db->getEscaped($username)."' LIMIT 1");
		}
		
		// Bind the post array to the user object
		$post = array();
		$post['name'] = $data->name;
		if (trim($post['name']) == '')
			$post['name'] = $email;
		$post['email'] = $email;
		$post['username'] = $username;
		$post['password']  = JUserHelper::genRandomPassword(8);
		$original = $post['password'];
		$post['password2'] = $post['password'];
		if (!$user->bind($post, 'usertype'))
			JError::raiseError(500, $user->getError());

		// Set some initial user values
		$user->set('id', 0);
		
		if (jtourHelper::isJ16())
		{
			$usersConfig = JComponentHelper::getParams('com_users');
			$user->set('groups', array($usersConfig->get('new_usertype', 2)));
		}
		else
		{
			$authorize =& JFactory::getACL();
			// Initialize new usertype setting
			$usersConfig =& JComponentHelper::getParams('com_users');
			$newUsertype = $usersConfig->get('new_usertype');
			if (!$newUsertype)
				$newUsertype = 'Registered';
			
			$user->set('usertype', '');
			$user->set('gid', $authorize->get_group_id('', $newUsertype, 'ARO'));
		}

		$date =& JFactory::getDate();
		$user->set('registerDate', $date->toMySQL());

		// If user activation is turned on, we need to set the activation information
		$useractivation = $usersConfig->get('useractivation');
		if ($useractivation == '1')
		{
			$user->set('activation', JUtility::getHash($post['password']));
			$user->set('block', '1');
		}
		$user->set('lastvisitDate', '0000-00-00 00:00:00');

		// If there was an error with registration, set the message
		if (!$user->save())
		{
			return false;
			JError::raiseWarning('', JText::_($user->getError()));
		}
		
		// Hack for community builder - approve the user so that he can login
		if (file_exists(JPATH_SITE.DS.'components'.DS.'com_comprofiler'.DS.'comprofiler.php'))
		{
		   $db->setQuery("INSERT INTO #__comprofiler SET approved = 1 , user_id = ".$user->get('id')." , id = ".$user->get('id')." , confirmed = 1");
		   $db->query();
		}
		
		// Send registration confirmation mail
		$password = $original;
		// Disallow control chars in the email
		$password = preg_replace('/[\x00-\x1F\x7F]/', '', $password);
		
		if (jtourHelper::getConfig('choose_password') && !empty($data->password))
		{
			$db->setQuery("UPDATE #__users SET `password`='".$db->getEscaped($data->password)."' WHERE `id`='".$user->get('id')."'");
			$db->query();
			$password = JText::_('RSM_HIDDEN_PASSWORD_TEXT');
		}
		
		jtour::sendUserEmail($user, $password, $data->fields);
		jtour::createUserData($user->get('id'), $data->fields);
		
		return $user->get('id');
	}
	
	function sendUserEmail(&$user, $password, $fields)
	{
		$mainframe =& JFactory::getApplication();
		
		$lang =& JFactory::getLanguage();
		$lang->load('com_jtour', JPATH_SITE);

		$db =& JFactory::getDBO();

		$name 		= $user->get('name');
		$email 		= $user->get('email');
		$username 	= $user->get('username');

		$usersConfig 	= &JComponentHelper::getParams('com_users');
		$sitename 		= $mainframe->getCfg('sitename');
		$useractivation = $usersConfig->get('useractivation');
		$mailfrom 		= $mainframe->getCfg('mailfrom');
		$fromname 		= $mainframe->getCfg('fromname');
		$siteURL		= JURI::base();
		if (JPATH_BASE == JPATH_ADMINISTRATOR && strpos($siteURL, '/administrator') !== false)
			$siteURL = substr($siteURL, 0, -14);

		$subject = JText::sprintf('RSM_NEW_EMAIL_SUBJECT', $name, $sitename);
		$subject = html_entity_decode($subject, ENT_QUOTES);

		if ($useractivation == 1)
		{
			if (jtourHelper::isJ16())
				$activation_url = '<a href="'.$siteURL.'index.php?option=com_users&task=registration.activate&token='.$user->get('activation').'">'.$siteURL.'index.php?option=com_users&task=registration.activate&token='.$user->get('activation').'</a>';
			else
				$activation_url = '<a href="'.$siteURL.'index.php?option=com_user&task=activate&activation='.$user->get('activation').'">'.$siteURL.'index.php?option=com_user&task=activate&activation='.$user->get('activation').'</a>';
			$message = JText::sprintf('RSM_NEW_EMAIL_ACTIVATE', $name, $sitename, $activation_url, $siteURL, $username, $password);
		}
		else
			$message = JText::sprintf('RSM_NEW_EMAIL', $name, $sitename, $siteURL, $username, $password);
		
		$replace = array();
		$with = array();
		if ($fields)
		foreach ($fields as $field => $value)
		{
			$replace[] = '{'.$field.'}';
			$with[] = is_array($value) ? implode(",", $value) : $value;
		}
		$message = str_replace($replace, $with, $message);
		
		$message = html_entity_decode($message, ENT_QUOTES);

		// Get all Super Administrators
		$db->setQuery("SELECT name, email, sendEmail FROM #__users WHERE LOWER(usertype) = 'super administrator'");
		$rows = $db->loadObjectList();

		// Send email to user
		if (!$mailfrom || !$fromname)
		{
			$fromname = $rows[0]->name;
			$mailfrom = $rows[0]->email;
		}
		JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message, true);

		$lang->load('com_user', JPATH_SITE);
		$lang->load('com_users', JPATH_SITE);
		
		// Send notification to all Administrators
		$subject2 = JText::sprintf('Account details for', $name, $sitename);
		$subject2 = html_entity_decode($subject2, ENT_QUOTES);
		foreach ($rows as $row)
			if ($row->sendEmail)
			{
				$message2 = JText::sprintf('SEND_MSG_ADMIN', $row->name, $sitename, $name, $email, $username);
				$message2 = html_entity_decode($message2, ENT_QUOTES);
				JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2, true);
			}
	}
	
	function createUserData($user_id, $post)
	{
		$db 	 =& JFactory::getDBO();
		$user_id = (int) $user_id;
		
		$db->setQuery("SELECT `user_id` FROM #__jtour_users WHERE `user_id`='".$user_id."'");
		if (!$db->loadResult())
		{
			$db->setQuery("INSERT INTO #__jtour_users SET `user_id`='".$user_id."'");
			$db->query();
		}
		
		$columns = array();
		
		$db->setQuery("SELECT * FROM #__jtour_fields WHERE published='1' ORDER BY ordering");
		$fields = $db->loadObjectList();
		foreach ($fields as $field)
		{
			if (!isset($post[$field->name]))
				continue;
			
			if (is_array($post[$field->name]))
				$post[$field->name] = implode("\n", $post[$field->name]);
			
			$columns[] = "`f".$field->id."`='".$db->getEscaped($post[$field->name])."'";
		}
		
		$db->setQuery("UPDATE #__jtour_users SET ".implode(", ", $columns)." WHERE user_id='".$user_id."' LIMIT 1");		
		$db->query();
	}
	
	function updateGid($user_id, $gid, $unblock=false)
	{
		$db 	 	 =& JFactory::getDBO();
		$user_id 	 = (int) $user_id;
		
		if (jtourHelper::isJ16())
		{
			jimport('joomla.user.helper');
			
			if (!is_array($gid))
				$gid = explode(',', $gid);
				
			JArrayHelper::toInteger($gid);
			JUserHelper::setUserGroups($user_id, $gid);
			if ($unblock)
			{
				$db->setQuery("UPDATE #__users SET `block`='0' WHERE `id`='".$user_id."'");
				$db->query();
			}
		}
		else
		{
			$gid	 	 = (int) $gid;
			$unblock_sql = $unblock ? ", `block`='0'" : "";
			
			// Update aro_id
			$db->setQuery("SELECT id FROM #__core_acl_aro WHERE `value`='".$user_id."' AND `section_value`='users'");
			$db->setQuery("UPDATE #__core_acl_groups_aro_map SET `group_id`='".$gid."' WHERE `aro_id`='".$db->loadResult()."'");
			$db->query();
			
			// Update gid
			$db->setQuery("SELECT `name` FROM #__core_acl_aro_groups WHERE `id`='".$gid."'");
			$db->setQuery("UPDATE #__users SET `gid`='".$gid."', `usertype`='".$db->getEscaped($db->loadResult())."' $unblock_sql WHERE `id`='".$user_id."'");
			$db->query();
		}
	}
	
	function disableUser($user_id)
	{
		$db =& JFactory::getDBO();
		$db->setQuery("UPDATE #__users SET `block`='1' WHERE `id`='".(int) $user_id."'");
		$db->query();
	}
	
	function enableUser($user_id)
	{
		$db =& JFactory::getDBO();
		$db->setQuery("UPDATE #__users SET `block`='0' WHERE `id`='".(int) $user_id."'");
		$db->query();
	}
}

class JTourValidation
{
	function website($url)
	{
		return preg_match('!^(http|https)://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?$!', $url);
	}
	
	function email($email)
	{
		jimport('joomla.mail.helper');
		return JMailHelper::isEmailAddress($email);
	}
	
	function numeric($number)
	{
		return preg_match("/^([0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]+?)?)$/", $number) || preg_match("/^([0-9]{1,3}(?:\.?[0-9]{3})*(?:,[0-9]+?)?)$/", $number);
	}
	
	function alphanumeric($string)
	{
		$string = str_replace(array("\n", "\r", "\t"), '', $string);
		return !preg_match('#([^a-zA-Z0-9 ])#', $string);
	}
	
	function alpha($string)
	{
		$string = str_replace(array("\n", "\r", "\t"), '', $string);
		return !preg_match('#([^a-zA-Z ])#', $string);
	}
}
?>