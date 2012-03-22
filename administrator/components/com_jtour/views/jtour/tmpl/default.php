<?php
/**
* @version 1.0.0
* @package JTour! 1.0.0
* @copyright (C) 2012 joomalungma.com
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
						<div class="icon hasTip" title="<?php echo JText::_('JTOUR_TOURS'); ?> :: <?php echo JText::_('JTOUR_TOURS_DESC'); ?>">
							<a href="index.php?option=com_jtour&amp;view=tours">
								<?php echo JHTML::_('image', 'administrator/components/com_jtour/assets/images/tours.png', JText::_('JTOUR_TOURS')); ?>
								<span><?php echo JText::_('JTOUR_TOURS'); ?></span>
							</a>
						</div>
					</div>
                    <div style="float: left">
                        <div class="icon hasTip" title="<?php echo JText::_('JTOUR_EXCURSIONS'); ?> :: <?php echo JText::_('JTOUR_EXCURSIONS_DESC'); ?>">
                            <a href="index.php?option=com_jtour&amp;view=excursions">
                                <?php echo JHTML::_('image', 'administrator/components/com_jtour/assets/images/excursions.png', JText::_('JTOUR_EXCURSIONS')); ?>
                                <span><?php echo JText::_('JTOUR_EXCURSIONS'); ?></span>
                            </a>
                        </div>
                    </div>
                    <div style="float: left">
                        <div class="icon hasTip" title="<?php echo JText::_('JTOUR_ORDERS'); ?> :: <?php echo JText::_('JTOUR_ORDERS_DESC'); ?>">
                            <a href="index.php?option=com_jtour&amp;view=orders">
                                <?php echo JHTML::_('image', 'administrator/components/com_jtour/assets/images/orders.png', JText::_('JTOUR_ORDERS')); ?>
                                <span><?php echo JText::_('JTOUR_ORDERS'); ?></span>
                            </a>
                        </div>
                    </div>
                    <div style="float: left">
                        <div class="icon hasTip" title="<?php echo JText::_('JTOUR_PAYMENTS'); ?> :: <?php echo JText::_('JTOUR_PAYMENTS_DESC'); ?>">
                            <a href="index.php?option=com_jtour&amp;view=payments">
                                <?php echo JHTML::_('image', 'administrator/components/com_jtour/assets/images/payments.png', JText::_('JTOUR_PAYMENTS')); ?>
                                <span><?php echo JText::_('JTOUR_PAYMENTS'); ?></span>
                            </a>
                        </div>
                    </div>
                    <div style="float: left">
                        <div class="icon hasTip" title="<?php echo JText::_('JTOUR_CONFIGURATION'); ?> :: <?php echo JText::_('JTOUR_CONFIGURATION_DESC'); ?>">
                            <a href="index.php?option=com_jtour&amp;view=configuration">
                                <?php echo JHTML::_('image', 'administrator/components/com_jtour/assets/images/configuration.png', JText::_('JTOUR_CONFIGURATION')); ?>
                                <span><?php echo JText::_('JTOUR_CONFIGURATION'); ?></span>
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
<span class="jtour_clear"></span>