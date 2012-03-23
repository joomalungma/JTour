<?php
/**
* @version 1.0.0
* @package jtour! 1.0.0
* @copyright (C) 2009-2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.html.html.tabs' );
JHTML::_('behavior.tooltip');
?>
<form action="index.php?option=com_jtour&view=configuration" method="post" name="adminForm" id="adminForm">
<?php
    echo JHtml::_('tabs.start', 'config-tabs-jtour_configuration', array('useCookie'=>1));
    echo JHtml::_('tabs.panel', JText::_('JTOUR_CONFIG_MAIN'), 'config-main');
?>
<fieldset class="adminform">
    <table class="admintable">
        <tr>
            <td width="100" align="right" class="key">
        <span class="hasTip" title="<?php echo JText::_('JTOUR_CONFIG_MODERATOR_EMAIL_DESC'); ?>">
        <?php echo JText::_('JTOUR_CONFIG_MODERATOR_EMAIL'); ?>
        </span>
            </td>
            <td>
                <input class="text_area" type="text" name="moderatorEmail" id="moderatorEmail" size="35" value="<?php echo $this->escape($this->config->moderatorEmail); ?>" />
            </td>
        </tr>
    </table>
</fieldset>
<?php
    echo JHtml::_('tabs.panel', JText::_('JTOUR_CONFIG_CURRENCY'), 'config-currency');
?>
<div class="width-60 fltlft">
<fieldset class="adminform">
    <table class="admintable" id="currencyTable" width="100%" cellspacing="0" cellpadding="0">
        <tr align="center">
            <th>
                <span class="hasTip" title="<?php echo JText::_('JTOUR_CONFIG_CURRENCY_NAME_DESC'); ?>">
                <?php echo JText::_('JTOUR_CONFIG_CURRENCY_NAME'); ?>
                </span>
            </th>
            <th>
                <span class="hasTip" title="<?php echo JText::_('JTOUR_CONFIG_CURRENCY_RATE_DESC'); ?>">
                <?php echo JText::_('JTOUR_CONFIG_CURRENCY_RATE'); ?>
                </span>
            </th>
            <th>
                <span class="hasTip" title="<?php echo JText::_('JTOUR_CONFIG_CURRENCY_DEFAULT_DESC'); ?>">
                <?php echo JText::_('JTOUR_CONFIG_CURRENCY_DEFAULT'); ?>
                </span>
            </th>
            <th>
                <span class="hasTip" title="<?php echo JText::_('JTOUR_CONFIG_CURRENCY_REMOVE_DESC'); ?>">
                <?php echo JText::_('JTOUR_CONFIG_CURRENCY_REMOVE'); ?>
                </span>
            </th>
        </tr>
        <?php
            if(!empty($this->currency)) :
            foreach ($this->currency as $name=>$rate) :
        ?>
        <tr align="center" class="highlighted">
            <td>
                <input class="text_area" type="text" name="currency[name][]" size="35" value="<?php echo $this->escape($name); ?>" />
            </td>
            <td>
                <input class="text_area" type="text" name="currency[rate][]" size="35" value="<?php echo $this->escape($rate); ?>" />
            </td>
            <td>
                <input type="radio" name="currencyDefault[]" value="0" onclick="setDefaultCurrency(this);" <?= $name==$this->config->currencyDefault ? 'checked="checked"' : '';?> />
            </td>
            <td>
                <span class="config_remove_btn" onclick="removeCurrency(this);"></span>
            </td>
        </tr>
        <?php
            endforeach;
            endif;
        ?>
    </table>
    <input name="currencyDefaultIndex" id="currencyDefaultIndex" type="hidden" value=""/>
</fieldset>
</div>
<div class="width-40 fltrt">
    <fieldset class="adminform">
        <div class="button2-left">
            <div class="blank">
                <a class="hasTip" title="<?= JText::_('JTOUR_ADD_CURRENCY_DESC');?>" onclick="addCurrency();"><span class="btn_add"></span><?= JTEXT::_('JTOUR_ADD_CURRENCY');?></a>
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
<input type="hidden" name="view" value="jtour" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="configuration" />
<input type="hidden" name="tabposition" id="tabposition" value="0" />
</form>

<script type="text/javascript">
Joomla.submitbutton = function submitbutton(pressbutton)
{
	var form = document.adminForm;
    elements = form.elements;
    k=0;
    res = 0;
    for(i=0; i < elements.length; i++)
    {
        if(elements[i].name === 'currencyDefault[]')
        {
            if(elements[i].value == 1){ res = k};
            k++;
        }
    }
	form.currencyDefaultIndex.value = res;
	if (pressbutton == 'cancel')
	{
		submitform(pressbutton);
		return;
	}

    submitform(pressbutton);
}
function removeCurrency(td)
{
    if (!confirm ('<?= JText::_("JTOUR_ARE_YOU SURE");?>')) return;
    /* todo check no remove default currency */
    tr = td.parentNode.parentNode;
    tr.parentNode.removeChild(tr);
}
function addCurrency()
{
    el = window.document.getElementById('currencyTable');
    //tr = window.document.createElement('tr');
    lastRow = el.rows.length;
    indexOfRow = lastRow;
    tr = el.insertRow(lastRow);
    tr.className = 'highlighted';
    tr.align = "center";
    tdName = window.document.createElement('td');
    tdName.innerHTML = '<input class="text_area" type="text" name="currency[name][]" size="35" value="" />';
    tdRate = window.document.createElement('td');
    tdRate.innerHTML = '<input class="text_area" type="text" name="currency[rate][]" size="35" value="" />';
    tdDefault = window.document.createElement('td');
    tdDefault.innerHTML = '<input type="radio" name="currencyDefault[]" value="0" onclick="setDefaultCurrency(this);"/>';
    tdRemove = window.document.createElement('td');
    tdRemove.innerHTML = '<span class="config_remove_btn" onclick="removeCurrency(this);"></span>';
    tr.appendChild(tdName);
    tr.appendChild(tdRate);
    tr.appendChild(tdDefault);
    tr.appendChild(tdRemove);
}
function setDefaultCurrency(el)
{
    elements = window.document.getElementById('adminForm').elements;
    for(i=0; i < elements.length; i++)
    {
        if(elements[i].name === 'currencyDefault[]') elements[i].value = 0;
    }
    el.value = 1;
}
</script>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>