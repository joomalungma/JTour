<?php
/**
* @version 1.0.0
* @package JTour! 1.0.0
* @copyright (C) 2009-2010 http://joomalungma.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

?>

</td>
<td width="50%" valign="top" align="center">

<form action="index.php?option=com_rsmembership" method="post" name="adminForm">
<table border="1" width="100%" class="thisform">
	<tr class="thisform">
		<th class="cpanel" colspan="2"><?php echo _JTOUR_PRODUCT . ' ' . _JTOUR_VERSION_LONG. ' rev ' . _JTOUR_VERSION; ?></th></td>
	 </tr>
	 <tr class="thisform"><td bgcolor="#FFFFFF" colspan="2"><br />
  <div style="width=100%" align="center">
  <img src="../administrator/components/com_rsmembership/assets/images/rsmembership.jpg" align="middle" alt="RSMembership! logo"/>
  <br /><br /></div>
  </td></tr>
	 <tr class="thisform">
		<td width="120" bgcolor="#FFFFFF"><?php echo JText::_('RSM_INSTALLED_VERSION'); ?></td>
		<td bgcolor="#FFFFFF"><?php echo _JTOUR_VERSION_LONG; ?></td>
	 </tr>
	 <tr class="thisform">
		<td bgcolor="#FFFFFF"><?php echo JText::_('RSM_COPYRIGHT'); ?></td>
		<td bgcolor="#FFFFFF"><?php echo _JTOUR_COPYRIGHT;?></td>
	 </tr>
	 <tr class="thisform">
		<td bgcolor="#FFFFFF"><?php echo JText::_('RSM_LICENSE'); ?></td>
		<td bgcolor="#FFFFFF"><?php echo _JTOUR_LICENSE;?></td>
	 </tr>
	 <tr class="thisform">
		<td valign="top" bgcolor="#FFFFFF"><?php echo JText::_('RSM_AUTHOR'); ?></td>
		<td bgcolor="#FFFFFF"><?php echo _JTOUR_AUTHOR;?></td>
	 </tr>
	 <tr class="<?php echo (!$this->code) ? 'thisformError' : 'thisformOk'; ?>">
		<td valign="top"><?php echo JText::_('RSM_YOUR_CODE'); ?></td>
		<td>
			<?php echo (!$this->code) ? '<input type="text" name="global_register_code" value="" />': $this->code; ?>
		</td>
	 </tr>
	 <tr class="<?php echo (!$this->code) ? 'thisformError' : 'thisformOk'; ?>">
		<td valign="top">&nbsp;</td>
		<td>
			<?php if (!$this->code) { ?>
			<input type="submit" name="register" value="<?php echo JText::_('RSM_UPDATE_REGISTRATION');?>" /><br/>
			<?php } else { ?>
			<input type="button" name="register" value="<?php echo JText::_('RSM_MODIFY_REGISTRATION');?>" onclick="javascript:submitbutton('saveRegistration');"/>
			<?php }	?>
		</td>
	 </tr>
  </table>
  <p align="center"><a href="http://www.rsjoomla.com/joomla-components/joomla-security.html" target="_blank"><img src="components/com_rsmembership/assets/images/rsfirewall-approved.gif" align="middle" alt="RSFirewall! Approved"/></a></p>
<input type="hidden" name="filetype" value="rsmembershipupdate"/>
<input type="hidden" name="task" value="saveRegistration"/>
<input type="hidden" name="option" value="com_rsmembership"/>
</form>

</td>
</tr>
</table>