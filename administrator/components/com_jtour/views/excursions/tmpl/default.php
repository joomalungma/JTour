<?php
/**
 * @version 1.0.1
 * @package JTour! 1.0.1
 * @copyright (C) 2012 joomalungma.com
 * @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die('Restricted access');
?>

<form action="<?php echo JRoute::_('index.php?option=com_jtour&view=excursions'); ?>" method="post" name="adminForm">
	<table class="adminform">
		<tr>
			<td width="100%">
			<?php echo JText::_( 'SEARCH' ); ?>
			<input type="text" name="search" id="search" value="<?php echo $this->filter_word; ?>" class="text_area" onChange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap"><?php echo $this->lists['state']; ?></td>
		</tr>
	</table>
	<div id="editcell1">
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5"><?php echo JText::_( '#' ); ?></th>
				<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(<?php echo count($this->excursions); ?>);"/></th>
                <th><?php echo JHTML::_('grid.sort', 'JTOUR_EXCURSION_NAME', 'name', $this->sortOrder, $this->sortColumn); ?></th>
                <th width="120" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'JTOUR_EXCURSION_MODIFIED', 'modified', $this->sortOrder, $this->sortColumn); ?></th>
				<th width="120" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'JTOUR_EXCURSION_PRICE', 'price', $this->sortOrder, $this->sortColumn); ?></th>
                <th width="120"><?php echo JText::_('JTOUR_EXCURSION_DURATION'); ?></th>
				<th width="80"><?php echo JText::_('Published'); ?></th>
			</tr>
			</thead>
	<?php
	$k = 0;
	$i = 0;
	$n = count($this->excursions);
	foreach ($this->excursions as $row)
	{
	?>
		<tr class="row<?php echo $k; ?>">
			<td><?php echo $this->pagination->getRowOffset($i); ?></td>
			<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
            <td><a href="<?php echo JRoute::_('index.php?option=com_jtour&controller=excursions&task=edit&cid='.$row->id); ?>"><?php echo $row->name != '' ? $row->name : JText::_('JTOUR_NO_TITLE'); ?></a></td>
			<td nowrap="nowrap"><?php echo $row->modified ?></td>
            <td nowrap="nowrap"><?php echo $row->price ?></td>
            <td nowrap="nowrap"><?php echo $row->duration ?></td>
			<td align="center"><?php echo JHTML::_('grid.published', $row, $i); ?></td>
		</tr>
	<?php
		$i++;
		$k=1-$k;
	}
	?>
		<tfoot>
			<tr>
				<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		</table>
	</div>
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_jtour" />
	<input type="hidden" name="view" value="excursions" />
	<input type="hidden" name="controller" value="excursions" />
	<input type="hidden" name="task" value="" />
	
	<input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortOrder; ?>" />
</form>