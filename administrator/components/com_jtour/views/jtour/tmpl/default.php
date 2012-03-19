<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
?>

<table>
<tr>
	<td width="50%" valign="top">

<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td valign="top">
		<table class="adminlist">
			<tr>
				<td>
					<div id="cpanel">
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_TRANSACTIONS'); ?> :: <?php echo JText::_('RSM_TRANSACTIONS_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=transactions">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/transactions.png', JText::_('RSM_TRANSACTIONS')); ?>
								<span><?php echo JText::_('RSM_TRANSACTIONS'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_MEMBERSHIPS'); ?> :: <?php echo JText::_('RSM_MEMBERSHIPS_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=memberships">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/memberships.png', JText::_('RSM_MEMBERSHIPS')); ?>
								<span><?php echo JText::_('RSM_MEMBERSHIPS'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_CATEGORIES'); ?> :: <?php echo JText::_('RSM_CATEGORIES_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=categories">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/category.png', JText::_('RSM_CATEGORIES')); ?>
								<span><?php echo JText::_('RSM_CATEGORIES'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_EXTRAS'); ?> :: <?php echo JText::_('RSM_MEMBERSHIP_EXTRAS_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=extras">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/extras.png', JText::_('RSM_MEMBERSHIP_EXTRAS')); ?>
								<span><?php echo JText::_('RSM_MEMBERSHIP_EXTRAS'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_MEMBERSHIP_UPGRADES'); ?> :: <?php echo JText::_('RSM_MEMBERSHIP_UPGRADES_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=upgrades">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/upgrades.png', JText::_('RSM_MEMBERSHIP_UPGRADES')); ?>
								<span><?php echo JText::_('RSM_MEMBERSHIP_UPGRADES'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_COUPONS'); ?> :: <?php echo JText::_('RSM_COUPONS_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=coupons">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/coupons.png', JText::_('RSM_COUPONS')); ?>
								<span><?php echo JText::_('RSM_COUPONS'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_PAYMENT_INTEGRATIONS'); ?> :: <?php echo JText::_('RSM_PAYMENT_INTEGRATIONS_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=payments">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/payments.png', JText::_('RSM_PAYMENT_INTEGRATIONS')); ?>
								<span><?php echo JText::_('RSM_PAYMENT_INTEGRATIONS'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_FILES'); ?> :: <?php echo JText::_('RSM_FILES_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=files">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/files.png', JText::_('RSM_FILES')); ?>
								<span><?php echo JText::_('RSM_FILES'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_FILE_TERMS'); ?> :: <?php echo JText::_('RSM_FILE_TERMS_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=terms">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/terms.png', JText::_('RSM_FILE_TERMS')); ?>
								<span><?php echo JText::_('RSM_FILE_TERMS'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_USERS'); ?> :: <?php echo JText::_('RSM_USERS_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=users">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/users.png', JText::_('RSM_USERS')); ?>
								<span><?php echo JText::_('RSM_USERS'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_FIELDS'); ?> :: <?php echo JText::_('RSM_FIELDS_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=fields">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/fields.png', JText::_('RSM_FIELDS')); ?>
								<span><?php echo JText::_('RSM_FIELDS'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_REPORTS'); ?> :: <?php echo JText::_('RSM_REPORTS_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=reports">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/reports.png', JText::_('RSM_REPORTS')); ?>
								<span><?php echo JText::_('RSM_REPORTS'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_CONFIGURATION'); ?> :: <?php echo JText::_('RSM_CONFIGURATION_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=configuration">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/configuration.png', JText::_('RSM_CONFIGURATION')); ?>
								<span><?php echo JText::_('RSM_CONFIGURATION'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_UPDATES'); ?> :: <?php echo JText::_('RSM_UPDATES_DESC'); ?>">
							<a href="index.php?option=com_rsmembership&amp;view=updates">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/updates.png', JText::_('RSM_UPDATES')); ?>
								<span><?php echo JText::_('RSM_UPDATES'); ?></span>
							</a>
						</div>
					</div>
					<div style="float: left">
						<div class="icon hasTip" title="<?php echo JText::_('RSM_GO_TO_FRONTEND'); ?> :: <?php echo JText::_('RSM_GO_TO_FRONTEND_DESC'); ?>">
							<a href="<?php echo JURI :: root(); ?>index.php?option=com_rsmembership" target="_blank">
								<?php echo JHTML::_('image', 'administrator/components/com_rsmembership/assets/images/frontend.png', JText::_('RSM_GO_TO_FRONTEND')); ?>
								<span><?php echo JText::_('RSM_GO_TO_FRONTEND'); ?></span>
							</a>
						</div>
					</div>
					</div>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

<span class="rsmembership_clear"></span>