<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_userxtd
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// no direct access
defined('_JEXEC') or die;



// Init some API objects
// ================================================================================
$app 	= JFactory::getApplication() ;
$date 	= JFactory::getDate( 'now' , JFactory::getConfig()->get('offset') ) ;
$doc 	= JFactory::getDocument();
$uri 	= JFactory::getURI() ;
$user	= JFactory::getUser();
$userId	= $user->get('id');



// List Control
// ================================================================================
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_userxtd');
$saveOrder	= $listOrder == 'a.ordering' || ($listOrder == 'a.lft' && $listDirn == 'asc');
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$nested		= $this->state->get('items.nested') ;
$orderCol 	= $nested ? 'a.lft' : 'a.ordering' ;
$show_root	= JRequest::getVar('show_root') ;

?>

<!-- List Table -->
<table class="table table-striped table-bordered adminlist" id="itemList">
	<thead>
		<tr>
			
			<th width="1%">
				<input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
			</th>
			
			<th>
				<?php echo JHtml::_('grid.sort',  'COM_USERXTD_USER_ID', 'a.user_id', $listDirn, $listOrder); ?>
			</th>
			
			<th class="nowrap">
				<?php echo JHtml::_('grid.sort',  'COM_USERXTD_USER_USERNAME', 'd.name', $listDirn, $listOrder); ?>
			</th>
			
			<th >
				<?php echo JHtml::_('grid.sort',  'LIB_WINDWALKER_FIELD_ATTR_NAME', 'b.name', $listDirn, $listOrder); ?>
			</th>
			
			<th >
				<?php echo JHtml::_('grid.sort',  'LIB_WINDWALKER_FIELD_ATTR_LABEL', 'b.label', $listDirn, $listOrder); ?>
			</th>
			
			<th >
				<?php echo JHtml::_('grid.sort',  'LIB_WINDWALKER_FIELD_ATTR_VALUE', 'a.value', $listDirn, $listOrder); ?>
			</th>
			
			<th >
				<?php echo JHtml::_('grid.sort',  'JCATEGORY', 'c.id', $listDirn, $listOrder); ?>
			</th>
			
			<th width="1%" class="nowrap">
				<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
			</th>
			
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="15">
				<div class="pull-left">
					<?php echo $this->pagination->getListFooter(); ?>
				</div>
				
				<?php if( JVERSION >= 3 ): ?>
				<!-- Limit Box -->
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
				<?php endif; ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php foreach ($this->items as $i => $item) :
		
		$item = new JObject($item);
		
		$ordering	= ($listOrder == $orderCol);
		$canEdit	= $user->authorise('core.edit',			'com_userxtd') ;
		$canCheckin	= $user->authorise('core.manage',		'com_userxtd') ;
		$canChange	= $user->authorise('core.edit.state',	'com_userxtd') ;
		$canEditOwn = $user->authorise('core.edit.own',		'com_userxtd') ;
		?>
		<tr class="row<?php echo $i % 2; ?>">
			<td class="center">
				<?php echo JHtml::_('grid.id', $i, $item->a_id); ?>
			</td>
			
			<td>
				<?php echo $item->a_user_id; ?>
			</td>
			
			<td>
				<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.$item->d_id); ?>" target="_blank">
					<?php echo $item->d_name; ?>
				</a>
				(<?php echo $item->d_username; ?>)
			</td>
			
			<td>
				<?php echo $item->a_key; ?>
			</td>
			
			<td>
				<?php echo $item->b_title; ?>
			</td>
			
			<td>
				<?php echo $item->a_value; ?>
			</td>
			
			<!--CATEGORY-->
			<td>
				<?php echo $item->c_title; ?>
			</td>
			
			<td>
				<?php echo $item->a_id; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>