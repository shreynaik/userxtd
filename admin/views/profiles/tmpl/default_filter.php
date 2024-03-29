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

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';

$app 	= JFactory::getApplication() ;
$layout = JRequest::getVar('layout') ;

// For Joomla!3.0
// ================================================================================
if( JVERSION >= 3 ) {
	$sortFields = $this->getSortFields();
}
?>
<div id="filter-bar" class="btn-toolbar">
	<!-- Search Input -->
	
	<div class="btn-group pull-left fltlft">
		<?php
			$field = $this->filter['search']->getField('field') ;
			echo $field->label . ' ' ;
		?>
	</div>
	
	<div class="btn-group pull-left fltlft">
		<?php
			if( !$this->state->get('search.fulltext') ){
				echo $field->input ;
			}
		?>
	</div>
	
	<div class="btn-group pull-left fltlft">
		<?php 
			$index = $this->filter['search']->getField('index') ;
			echo $index->input ;
		?>
	</div>
	
	
	<!-- Search Button -->
	<?php if( JVERSION >= 3 ): ?>
	<div class="btn-group pull-left hidden-phone">
		<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
		<button class="btn tip hasTooltip" type="button" onclick="document.id('search_index').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
	</div>
	<?php else: ?>
	
		<button type="btn submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
		<button type="btn button" onclick="document.id('search_index').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
	
	<?php endif; ?>
	
	
	<?php if( $layout != 'modal' && JVERSION >= 3 && $app->isAdmin() ): ?>
	
		<?php if( $layout != 'modal' ): ?>
	
			<?php //if( false ): // Do not show this. ?>
			<!-- Filter Order Dir -->
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			
			
			<!-- Filter Order -->
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
			<?php //endif; ?>
			
		
		<?php endif; ?>
	
	<?php else: ?>
		
		<?php if( $layout == 'modal' || JVERSION < 3 || (JVERSION >= 3 && $app->isSite()) ): // Do not show this. ?>
		
			<?php if( JVERSION >= 3 ): ?>
			<div class="clearfix"></div>
			<?php endif; ?>
			
			<!-- Show Filters -->
			<?php foreach( $this->filter['filter']->getFieldset('filter') as $filter ): ?>
			<div class="btn-group pull-right span3 fltrt">
				<?php echo $filter->input; ?>
			</div>
			<?php endforeach; ?>
		<?php endif; ?>
	
	<?php endif; ?>
</div>
<div class="clearfix"> </div>