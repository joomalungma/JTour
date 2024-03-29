<?php
/**
* @version 1.0.0
* @package RSMembership! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.html.html.tabs' );

JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');


?>

<script type="text/javascript">
function Joomla.submitbutton(pressbutton)
{
	var form = document.adminForm;
	
	if (pressbutton == 'cancel')
	{
		submitform(pressbutton);
		return;
	}

	// do field validation
	submitform(pressbutton);
}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jtour&controller=tours&task=edit'); ?>" method="post" name="adminForm" id="adminForm">
    <?php
        echo JHtml::_('tabs.start', 'tour-tabs-com_jtour_tour', array('useCookie'=>1));
        echo JHtml::_('tabs.panel', JText::_('JTOUR_TOUR_MAIN'), 'tour-main');
    ;?>
    <fieldset class="adminform">
        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="adminTable">
		<tr>
			<td class="key" width="200"><span class="hasTip" title="<?php echo JText::_('JTOUR_TOUR_NAME_DESC'); ?>"><label for="name"><?php echo JText::_('JTOUR_TOUR_NAME'); ?></label></span></td>
			<td><input type="text" name="name" value="<?php echo $this->escape($this->row->name); ?>" id="name" size="40" maxlength="255" /></td>
		</tr>
		<tr>
			<td class="key" width="200"><span class="hasTip" title="<?php echo JText::_('JTOUR_TOUR_PRICE_DESC'); ?>"><label for="price"><?php echo JText::_('JTOUR_TOUR_PRICE'); ?></label></span></td>
			<td><input type="text" name="price" value="<?php echo $this->escape($this->row->price); ?>" id="price" size="10" maxlength="255" />&nbsp;<?= $this->currency;?></td>
		</tr>
        <tr>
            <td class="key" width="200"><span class="hasTip" title="<?php echo JText::_('JTOUR_TOUR_DURATION_DESC'); ?>"><label for="duration"><?php echo JText::_('JTOUR_TOUR_DURATION'); ?></label></span></td>
            <td><?php echo $this->lists['duration']; ?>&nbsp;<?= JText::_('JTOUR_DAYS');?></td>
        </tr>
        <tr>
			<td class="key" width="200"><span class="hasTip" title="<?php echo JText::_('PUBLISHED_DESC'); ?>"><label for="published"><?php echo JText::_('PUBLISHED'); ?></label></span></td>
			<td><?php echo $this->lists['published']; ?></td>
		</tr>
        <tr>
			<td class="key" width="200"><span class="hasTip" title="<?php echo JText::_('JTOUR_TOUR__DESCRIPTION_DESC'); ?>"><label for="description"><?php echo JText::_('JTOUR_TOUR__DESCRIPTION'); ?></label></span></td>
			<td><?php echo $this->editor->display('description',$this->row->description,500,250,70,10); ?></td>
		</tr>
	</table>
    </fieldset>
    <?php
        echo JHtml::_('tabs.panel', JText::_('JTOUR_TOUR_EXCURSIONS'), 'tour-excursions');
    ;?>
    <div class="width-60 fltlft">
    <fieldset class="adminForm">
        <table class="adminTable" id="excursionsTable">
            <tr align="center">
                <th><?= JText::_('JTOUR_NAME');?></th>
                <th><?= JText::_('JTOUR_DURATION');?></th>
                <th><?= JText::_('JTOUR_REMOVE');?></th>
            </tr>
            <?php if(is_array($this->row->excursions)) :
                    foreach ($this->row->excursions as $excursion) :
                ;?>
                <tr class="highlighted" align="center">
                    <td>
                        <?php echo $excursion->name;?>
                        <input type="hidden" value="<?php echo $excursion->id;?>" name="excursions[]">
                    </td>
                    <td><?php echo $excursion->duration;?></td>
                    <td>
                        <span class="config_remove_btn" onclick="removeExcursion(this);"></span>
                    </td>
                </tr>
            <?php endforeach;
                endif;
            ?>
        </table>
    </fieldset>
    </div>
    <div class="width-40 fltrt">
        <fieldset class="adminform">
            <div class="button2-left">
                <div class="blank">
                    <a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 450}}" href="index.php?option=com_jtour&view=excursions&layout=modal&tmpl=component&8675aa960e3bad4312a832ac0caaac3f=1" title="<?= JText::_('JTOUR_ADD_EXCURSION_DESC');?>"><span class="btn_add"></span><?= JTEXT::_('JTOUR_ADD_EXCURSION');?></a>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="clr"></div>
    <?php
    echo JHtml::_('tabs.end');
    ?>
<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="option" value="com_jtour" />
<input type="hidden" name="controller" value="tours" />
<input type="hidden" name="task" value="edit" />
<input type="hidden" name="view" value="tours" />

<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
</form>
<script type="text/javascript">
function removeExcursion(td)
    {
        if (!confirm ('<?= JText::_("JTOUR_ARE_YOU SURE");?>')) return;
        tr = td.parentNode.parentNode;
        tr.parentNode.removeChild(tr);
    }
</script>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>