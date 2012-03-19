<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

function RSMembershipBuildRoute(&$query)
{
	$segments = array();
	
	if (!empty($query['task']))
		switch ($query['task'])
		{
			case 'subscribe':
				$segments[] = 'subscribe-to';
				$segments[] = $query['cid'];
			break;
			
			case 'validatesubscribe':
				$segments[] = 'subscribe-finish';
			break;
			
			case 'paymentredirect':
				$segments[] = 'payment-redirect';
			break;
			
			case 'payment':
				$segments[] = 'payment';
			break;
			
			case 'download':
				$segments[] = 'download';
				if ($query['from'] == 'membership')
					$segments[] = 'from-membership';
				elseif ($query['from'] == 'extra')
					$segments[] = 'from-membership-extra';
				$segments[] = $query['cid'];
			break;
			
			case 'thankyou':
				$segments[] = 'show-thank-you';
			break;
			
			case 'upgrade':
				$segments[] = 'upgrade-to';
				$segments[] = $query['cid'];
			break;
			
			case 'upgradepaymentredirect':
				$segments[] = 'upgrade-payment-redirect';
			break;
			
			case 'upgradepayment':
				$segments[] = 'upgrade-payment';
			break;
			
			case 'renew':
				$segments[] = 'renew';
				$segments[] = $query['cid'];
			break;
			
			case 'renewpaymentredirect':
				$segments[] = 'renew-payment-redirect';
			break;
			
			case 'renewpayment':
				$segments[] = 'renew-payment';
			break;
			
			case 'addextra':
				$segments[] = 'add-extra-to-membership';
				$segments[] = $query['cid'];
				$segments[] = $query['extra_id'];
			break;
			
			case 'addextrapaymentredirect':
				$segments[] = 'add-extra-payment-redirect';
			break;
			
			case 'addextrapayment':
				$segments[] = 'add-extra-payment';
			break;
			
			case 'validateuser':
				$segments[] = 'save-my-account';
			break;
		}
	
	if (!empty($query['view']))
		switch ($query['view'])
		{
			case 'membership':					
				$segments[] = 'view-membership-details';
				if (isset($query['catid']))
					$segments[] = $query['catid'];
				$segments[] = $query['cid'];
			break;
			
			case 'mymembership':
				if (!empty($query['path']))
				{
					$segments[] = 'browse-folders';
					if ($query['from'] == 'membership')
						$segments[] = 'from-membership';
					elseif ($query['from'] == 'extra')
						$segments[] = 'from-membership-extra';
				}
				else
				{
					$segments[] = 'view-my-membership-details';
				}
				$segments[] = $query['cid'];
			break;
			
			case 'mymemberships':
				$segments[] = 'view-my-memberships';
			break;
			
			case 'rsmembership':
				$segments[] = 'view-available-memberships';
				
				if (isset($query['catid']))
					$segments[] = $query['catid'];
			break;
			
			case 'terms':
				$segments[] = 'view-terms';
				$segments[] = $query['cid'];
			break;
			
			case 'user':
				$segments[] = 'view-my-account';
			break;
		}
	
	unset($query['task'], $query['cid'], $query['catid'], $query['view'], $query['from'], $query['extra_id']);
	
	return $segments;
}

function RSMembershipParseRoute($segments)
{
	$query = array();
	
	$segments[0] = str_replace(':', '-', $segments[0]);
	
	switch ($segments[0])
	{
		case 'subscribe-to':
			$query['task'] = 'subscribe';
			$query['cid'] = @$segments[1];
		break;
		
		case 'subscribe-finish':
			$query['task'] = 'validatesubscribe';
		break;
		
		case 'payment-redirect':
			$query['task'] = 'paymentredirect';
		break;
		
		case 'payment':
			$query['task'] = 'payment';
		break;
		
		case 'download':
			$query['task'] = 'download';
			$segments[1] = str_replace(':', '-', $segments[1]);
			if ($segments[1] == 'from-membership')
				$query['from'] = 'membership';
			elseif ($segments[1] == 'from-membership-extra')
				$query['from'] = 'extra';
			
			$query['cid'] = end($segments);
		break;
		
		case 'show-thank-you':
			$query['task'] = 'thankyou';
		break;
		
		case 'upgrade-to':
			$query['task'] = 'upgrade';
			$query['cid'] = $segments[1];
		break;
		
		case 'upgrade-payment-redirect':
			$query['task'] = 'upgradepaymentredirect';
		break;
		
		case 'upgrade-payment':
			$query['task'] = 'upgradepayment';
		break;
		
		case 'renew':
			$query['task'] = 'renew';
			$query['cid'] = $segments[1];
		break;
		
		case 'renew-payment-redirect':
			$query['task'] = 'renewpaymentredirect';
		break;
		
		case 'renew-payment':
			$query['task'] = 'renewpayment';
		break;
		
		case 'add-extra-to-membership':
			$query['task'] = 'addextra';
			$query['cid'] = $segments[1];
			$query['extra_id'] = @$segments[2];
		break;
		
		case 'add-extra-payment-redirect':
			$query['task'] = 'addextrapaymentredirect';
		break;
		
		case 'add-extra-payment':
			$query['task'] = 'addextrapayment';
		break;
		
		case 'view-membership-details':
			$query['view'] = 'membership';
			
			if (isset($segments[2]))
			{
				$query['catid'] = $segments[1];
				$query['cid'] = $segments[2];
			}
			else
			{
				$query['cid'] = $segments[1];
			}
		break;
		
		case 'view-my-membership-details':
			$query['view'] = 'mymembership';
			$query['cid'] = $segments[1];
		break;
		
		case 'browse-folders':
			$query['view'] = 'mymembership';
			$segments[1] = str_replace(':', '-', $segments[1]);
			if ($segments[1] == 'from-membership')
				$query['from'] = 'membership';
			elseif ($segments[1] == 'from-membership-extra')
				$query['from'] = 'extra';
			$query['cid'] = @$segments[2];
		break;
		
		case 'view-my-memberships':
			$query['view'] = 'mymemberships';
		break;
		
		case 'view-available-memberships':
			if (isset($segments[1]))
				$query['catid'] = $segments[1];
				
			$query['view'] = 'rsmembership';
		break;
		
		case 'view-terms':
			$query['view'] = 'terms';
			$query['cid'] = @$segments[1];
		break;
		
		case 'view-my-account':
			$query['view'] = 'user';
		break;
		
		case 'save-my-account':
			$query['task'] = 'validateuser';
		break;
	}
	
	return $query;
}
?>