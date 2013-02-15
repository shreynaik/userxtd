<?php
/**
 * @package     Windwalker.Framework
 * @subpackage  class
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');


class AKModelAdmin extends JModelAdmin
{
	public $component ;
	
	public $item_name ;
	
	public $list_name ;
	
	public $item ;
	
	public $category ;
	
	
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = null, $prefix = null, $config = array())
	{
		$prefix = $prefix 	? $prefix 	: ucfirst($this->component).'Table' ;
		$type 	= $type 	? $type 	: $this->item_name ;
		
		return parent::getTable( $type , $prefix , $config );
	}
	
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm("{$this->option}.{$this->item_name}", $this->item_name, array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}
	
	
	
	/*
	 * function getFields
	 * @param 
	 */
	
	public function getFields()
	{
		if(!empty($this->fields_name)) return $this->fields_name ;
		
		$xml_file 		= AKHelper::_('path.get').'/models/forms/'.$this->item_name.'.xml' ;
		$xml 			= JFactory::getXML( $xml_file );
		$fields 		= $xml->xpath('/form/fields');
		$fields_name 	= array();
		
		foreach( $fields as $field ):
			if( (string) $field['name'] != 'other' )
				$fields_name[] = (string) $field['name'] ;
		endforeach;
		
		return $this->fields_name = $fields_name ;
	}
	
	

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState("{$this->option}.edit.{$this->item_name}.data", array());
		
		if (empty($data)) 
		{
			$data = $this->getItem();
		}else{
			$data = new JObject($data);
			
			// If Error occured and resend, just return data.
			return $data ;
		}
		
		
		
		// Get params, convert $data->params['xxx'] to $data->param_xxx
		// ==========================================================================================
		if( isset($data->params) && is_array($data->params)){
			foreach( $data->params as $key => $param ):
				$key = 'param_'.$key ;
				if(empty($data->$key)){
					$data->$key = $param ;
				}
			endforeach;
		}
		
		
		
		// This seeting is for Fields Group
		// Convert data[field] to data[fields_group][field] then Jform can bind data into forms.
		// ==========================================================================================
		$fields = $this->getFields();
		
		foreach( $fields as $field ):
			$data->$field = clone $data ;
		endforeach;
		
		
		// Set FieldType if enable CCK Engine
		// ==========================================================================================
		if($this->getState('CCKEngine.enabled')){
			$this->setFieldType($data);
		}
		
		
		return $data;
	}
	
	
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		parent::populateState();
		
		
		// Set Nested Item
		$table = $this->getTable();
		if( $table instanceof JTableNested ){
			$nested = true ;
		}else{
			$nested = false ;
		}
		$this->setState( 'item.nested', $nested );
	}
	
	
	/**
     * Method to allow derived classes to preprocess the form.
     *
     * @param   JForm   $form   A JForm object.
     * @param   mixed   $data   The data expected for the form.
     * @param   string  $group  The name of the plugin group to import (defaults to "content").
     *
     * @return  void 
     *
     * @see     JFormField
     * @since   11.1
     * @throws  Exception if there is an error in the form event.
     */
    protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		parent::preprocessForm($form, $data, $group);
	}
	
	
	
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		return $this->item = parent::getItem($pk);
	}
	
	
	
	/*
	 * function setFieldType
	 * @param arg
	 */
	
	public function setFieldType($data = null, $key = 'field_type')
	{
		if(!$data) return false ;
		
		$type = JRequest::getVar($key) ;
		if(!$type) {
			if( !($data instanceof JObject) ){
				$data = new JObject($data);
			}
			
			$type = $data->get('field_type', 'text');
		}
		
		$type = $type ? $type : 'text';
		
		JRequest::setVar($key, $type, 'method', true) ;
	}
	
	
	
	/*
	 * function getCategory
	 * @param 
	 */
	
	public function getCategory($pk = null)
	{	
		if(!empty($this->category)){
			return $this->category ;
		}
		
		$pk = $pk ? $pk : $this->getItem()->catid ;
		
		$this->category  = JTable::getInstance('Category');
		
		if(!$this->category->load($pk) ) {
			$this->setError($this->category->getError());
            return false;
		}
		
		return $this->category ;
	}
	
	
	
	/**
     * Method to test whether a record can be deleted.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to delete the record. Defaults to the permission for the component.
     *
     * @since   12.2
     */
    protected function canDelete($record)
    {
        $user = JFactory::getUser();
        return $user->authorise('core.delete', $this->option.'.'.$this->item_name.'.'.$record->id);
    }
 
    /**
     * Method to test whether a record can be deleted.
     *
     * @param   object  $record  A record object.
     *
     * @return  boolean  True if allowed to change the state of the record. Defaults to the permission for the component.
     *
     * @since   12.2
     */
    protected function canEditState($record)
    {
        $user = JFactory::getUser();
        return $user->authorise('core.edit.state', $this->option.'.'.$this->item_name.'.'.$record->id);
	}
	
	
	
	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object	A record object.
	 *
	 * @return  array  An array of conditions to add to add to ordering queries.
	 * @since   1.6
	 */
	protected function getReorderConditions($table)
	{
		if(property_exists($table, 'catid')){
			$condition = array();
			$condition[] = 'catid = '.(int) $table->catid;
			return $condition;
		}
	}
	
	
	
	/**
	 * Method rebuild the entire nested set tree.
	 *
	 * @return  boolean  False on failure or error, true otherwise.
	 *
	 * @since   1.6
	 */
	public function rebuild()
	{
		// Set parent not 0
		$db = JFactory::getDbo();
		$q = $db->getQuery(true) ;
		
		$q->update('#__'.$this->component.'_'.$this->list_name)
			->set('parent_id=1')
			->set('level=1')
			->set('rgt=0')
			->set('lft=0')
			->where('id != 1')
			->where('parent_id = 0')
			;
		
		$db->setQuery($q);
		$db->query();
		
		
		// Get an instance of the table object.
		$table = $this->getTable();

		if (!$table->rebuild())
		{
			$this->setError($table->getError());
			return false;
		}
		
		
		// Clear the cache
		$this->cleanCache();

		return true;
	}
	
	
	/**
	 * Method to save the reordered nested set tree.
	 * First we save the new order values in the lft values of the changed ids.
	 * Then we invoke the table rebuild to implement the new ordering.
	 *
	 * @param   array    $idArray    An array of primary key ids.
	 * @param   integer  $lft_array  The lft value
	 *
	 * @return  boolean  False on failure or error, True otherwise
	 *
	 * @since   1.6
	*/
	public function saveorderNested($idArray = null, $lft_array = null)
	{
		// Get an instance of the table object.
		$table = $this->getTable();

		if (!$table->saveorder($idArray, $lft_array))
		{
			$this->setError($table->getError());
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}
	
	
	
	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		
		$date 	= JFactory::getDate( 'now' , JFactory::getConfig()->get('offset') ) ;
		$user 	= JFactory::getUser() ;
		$db 	= JFactory::getDbo();
		
		
		// alias
        if( isset($table->alias) ) {
			
			if(!$table->alias){
				$table->alias = JFilterOutput::stringURLSafe( trim($table->title) ) ;
			}else{
				$table->alias = JFilterOutput::stringURLSafe( trim($table->alias) ) ;
			}
			
			if(!$table->alias){
				$table->alias = JFilterOutput::stringURLSafe( $date->toSql(true) ) ;
			}
		}
		
		// created date
		if(isset($table->created) && !$table->created){
			$table->created = $date->toSql(true);
		}
		
		// modified date
		if(isset($table->modified) && $table->id){
			$table->modified = $date->toSql(true);
		}
		
		// created user
		if(isset($table->created_by) && !$table->created_by){
			$table->created_by = $user->get('id');
		}
		
		// modified user
		if(isset($table->modified_by) && $table->id){
			$table->modified_by = $user->get('id');
		}
		
		
		// Version
		if(isset($table->version)){
			$table->version++ ;
		}
		
		
		// Set Ordering or Nested ordering

		// set catid = parent->catid
		if( $table instanceof JTableNested ){
			$table->parent_id = $table->parent_id ? $table->parent_id : 1 ;
			
			$old = $this->getTable() ;
			$old->load($table->id) ;
			
			if($table->parent_id != $old->get('parent_id')) {
				$parent = $this->getTable();
				$parent->load( $table->parent_id );
				$table->catid = $parent->catid ;
				
				$table->setLocation( $table->parent_id , 'last-child' );
				
				// Rebuild the path for the category:
				if (!$table->rebuildPath())
				{
					$this->setError($table->getError());
					return false;
				}
			}
			
		}else{
			if (!$table->id) {
				// Set ordering to the last item if not set
				if (!$table->ordering) {
					$table->reorder('catid = '.(int) $table->catid.' AND published >= 0');
				}
			}
		}
		
		
		// Set Fields if CCKEngine Enabled
		if($this->getState('CCKEngine.enabled')) {
			AKHelper::_('fields.setFieldTable', $table) ;
		}
	}
	
	
	
	/**
     * Method to perform batch operations on an item or a set of items.
     *
     * @param   array  $commands  An array of commands to perform.
     * @param   array  $pks       An array of item ids.
     * @param   array  $contexts  An array of item contexts.
     *
     * @return  boolean  Returns true on success, false on failure.
     *
     * @since   12.2
     */
    public function batch($commands, $pks, $contexts)
	{		
		// Sanitize user ids.
        $pks = array_unique($pks);
        JArrayHelper::toInteger($pks);
 
        // Remove any values of zero.
        if (array_search(0, $pks, true))
        {
            unset($pks[array_search(0, $pks, true)]);
        }
 
        if (empty($pks))
        {
            $this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
            return false;
        }
 
        $done = array();
		
		
		if (!empty($commands['parent_id']))
        {
            $cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

            if ($cmd == 'c')
            {
                $result = $this->batchCopyNested($commands['parent_id'], $pks, $contexts);
                if (is_array($result))
                {
                    $pks = $result;
                }
                else
                {
                    return false;
                }
            }
            elseif ($cmd == 'm' && !$this->batchMoveNested($commands['parent_id'], $pks, $contexts))
            {
                return false;
            }
            $done = true;
        }
		
		if (!empty($commands['category_id']))
        {
            $cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');
 
            if ($cmd == 'c')
            {
                $result = $this->batchCopy($commands['category_id'], $pks, $contexts);
                if (is_array($result))
                {
                    $pks = $result;
                }
                else
                {
                    return false;
                }
            }
            elseif ($cmd == 'm' && !$this->batchMove($commands['category_id'], $pks, $contexts))
            {
                return false;
            }
			
			// If is Nested, rebuild all
			$table = $this->getTable();
			if($table instanceof JTableNested){
				$this->rebuild();
			}
			
            $done = true;
        }
 
        if (!empty($commands['assetgroup_id']))
        {
            if (!$this->batchAccess($commands['assetgroup_id'], $pks, $contexts))
            {
                return false;
            }
 
            $done = true;
        }
 
        if (!empty($commands['language_id']))
        {
            if (!$this->batchLanguage($commands['language_id'], $pks, $contexts))
            {
                return false;
            }
 
            $done = true;
        }
 
        if (!$done)
        {
            $this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
            return false;
        }
 
        // Clear the cache
        $this->cleanCache();
 
        return true;
	}
	
	
	
	/**
	 * Batch copy items to be another item's children.
	 *
	 * @param   integer  $value     The new item.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure.
	 *
	 * @since   3.0
	 */
	protected function batchCopyNested($value, $pks, $contexts)
	{
		$parentId 	= $value ? (int) $value : 1 ;
		$table 		= $this->getTable();
		$db 		= $this->getDbo();
		$user 		= JFactory::getUser();
		$extension 	= $this->option;
		$i = 0;

		// Check that the parent exists
		if ($parentId)
		{
			if (!$table->load($parentId))
			{
				if ($error = $table->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					// Non-fatal error
					$this->setError(JText::_('JGLOBAL_BATCH_MOVE_PARENT_NOT_FOUND'));
					$parentId = 0;
				}
			}
			// Check that user has create permission for parent category
			$canCreate = ($parentId == $table->getRootId()) ? $user->authorise('core.create', $extension) : $user->authorise('core.create', $extension . '.' . $this->item_name . '.' . $parentId);
			if (!$canCreate)
			{
				// Error since user cannot create in parent category
				$this->setError(JText::_($this->text_prefix.'_BATCH_CANNOT_CREATE'));
				return false;
			}
		}

		// If the parent is 0, set it to the ID of the root item in the tree
		if (empty($parentId))
		{
			if (!$parentId = $table->getRootId())
			{
				$this->setError($db->getErrorMsg());
				return false;
			}
			// Make sure we can create in root
			elseif (!$user->authorise('core.create', $extension))
			{
				$this->setError(JText::_($this->text_prefix.'_BATCH_CANNOT_CREATE'));
				return false;
			}
		}

		// We need to log the parent ID
		$parents = array();

		// Calculate the emergency stop count as a precaution against a runaway loop bug
		$query = $db->getQuery(true);
		$query->select('COUNT(id)');
		$query->from($db->quoteName("#__{$this->component}_{$this->list_name}"));
		$db->setQuery($query);

		try
		{
			$count = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		// Parent exists so we let's proceed
		while (!empty($pks) && $count > 0)
		{
			// Pop the first id off the stack
			$pk = array_shift($pks);

			$table->reset();

			// Check that the row actually exists
			if (!$table->load($pk))
			{
				if ($error = $table->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JGLOBAL_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Copy is a bit tricky, because we also need to copy the children
			$query->clear();
			$query->select('id');
			$query->from($db->quoteName("#__{$this->component}_{$this->list_name}"));
			$query->where('lft > ' . (int) $table->lft);
			$query->where('rgt < ' . (int) $table->rgt);
			$db->setQuery($query);
			$childIds = $db->loadColumn();

			// Add child ID's to the array only if they aren't already there.
			foreach ($childIds as $childId)
			{
				if (!in_array($childId, $pks))
				{
					array_push($pks, $childId);
				}
			}

			// Make a copy of the old ID and Parent ID
			$oldId = $table->id;
			$oldParentId = $table->parent_id;

			// Reset the id because we are making a copy.
			$table->id = 0;

			// If we a copying children, the Old ID will turn up in the parents list
			// otherwise it's a new top level item
			$table->parent_id = isset($parents[$oldParentId]) ? $parents[$oldParentId] : $parentId;

			// Set the new location in the tree for the node.
			$table->setLocation($table->parent_id, 'last-child');

			// TODO: Deal with ordering?
			//$table->ordering	= 1;
			$table->level 		= null;
			$table->asset_id 	= null;
			$table->lft 		= null;
			$table->rgt 		= null;

			// Alter the title & alias
			list($title, $alias) = $this->generateNewTitle($table->parent_id, $table->alias, $table->title);
			$table->title = $title;
			$table->alias = $alias;

			// Store the row.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}

			// Get the new item ID
			$newId = $table->get('id');

			// Add the new ID to the array
			$newIds[$i] = $newId;
			$i++;

			// Now we log the old 'parent' to the new 'parent'
			$parents[$oldId] = $table->id;
			$count--;
		}

		// Rebuild the hierarchy.
		if (!$table->rebuild())
		{
			$this->setError($table->getError());
			return false;
		}

		// Rebuild the tree path.
		if (!$table->rebuildPath($table->id))
		{
			$this->setError($table->getError());
			return false;
		}

		return $newIds;
	}
	
	
	
	/**
	 * Batch move items to be another item's children.
	 *
	 * @param   integer  $value     The new item ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	protected function batchMoveNested($value, $pks, $contexts)
	{
		$parentId = (int) $value;

		$table 	= $this->getTable();
		$db 	= $this->getDbo();
		$query 	= $db->getQuery(true);
		$user 	= JFactory::getUser();
		$extension = $this->option;

		
		// Check that the parent exists.
		if ($parentId)
		{
			if (!$table->load($parentId))
			{
				if ($error = $table->getError())
				{
					// Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// Non-fatal error
					$this->setError(JText::_('JGLOBAL_BATCH_MOVE_PARENT_NOT_FOUND'));
					$parentId = 0;
				}
			}
			// Check that user has create permission for parent category
			$canCreate = ($parentId == $table->getRootId()) ? $user->authorise('core.create', $extension) : $user->authorise('core.create', $extension . '.' . $this->item_name . '.' . $parentId);
			if (!$canCreate)
			{
				// Error since user cannot create in parent category
				$this->setError(JText::_($this->text_prefix.'_BATCH_CANNOT_CREATE'));
				return false;
			}

			// Check that user has edit permission for every category being moved
			// Note that the entire batch operation fails if any category lacks edit permission
			foreach ($pks as $pk)
			{
				if (!$user->authorise('core.edit', $extension . '.' . $this->item_name . '.' . $pk))
				{
					// Error since user cannot edit this category
					$this->setError(JText::_($this->text_prefix.'_BATCH_CANNOT_EDIT'));
					return false;
				}
			}
		}

		// We are going to store all the children and just move the category
		$children = array();

		// Parent exists so we let's proceed
		foreach ($pks as $pk)
		{
			// Check that the row actually exists
			if (!$table->load($pk))
			{
				if ($error = $table->getError())
				{
					// Fatal error
					$this->setError($error);
					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JGLOBAL_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Set the new location in the tree for the node.
			$table->setLocation($parentId, 'last-child');

			// Check if we are moving to a different parent
			if ($parentId != $table->parent_id)
			{
				// Add the child node ids to the children array.
				$query->clear();
				$query->select('id');
				$query->from($db->quoteName("#__{$this->component}_{$this->list_name}"));
				$query->where($db->quoteName('lft') .' BETWEEN ' . (int) $table->lft . ' AND ' . (int) $table->rgt);
				$db->setQuery($query);

				try
				{
					$children = array_merge($children, (array) $db->loadColumn());
				}
				catch (RuntimeException $e)
				{
					$this->setError($e->getMessage());
					return false;
				}
			}

			// Store the row.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}

			// Rebuild the tree path.
			if (!$table->rebuildPath())
			{
				$this->setError($table->getError());
				return false;
			}
		}

		// Process the child rows
		if (!empty($children))
		{
			// Remove any duplicates and sanitize ids.
			$children = array_unique($children);
			JArrayHelper::toInteger($children);
		}

		return true;
	}
}