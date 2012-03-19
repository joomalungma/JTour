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
					</div>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>

<span class="rsmembership_clear"></span>