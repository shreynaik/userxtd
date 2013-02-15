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

jimport('joomla.database.tablenested');

/**
 * field Table class
 */
class UserxtdTableField extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabase A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__userxtd_fields', 'id', $db);
	}
	
	/**
     * Method to compute the default name of the asset.
     * The default name is in the form table_name.id
     * where id is the value of the primary key of the table.
     *
     * @return  string 
     *
     * @since   11.1
     */
    protected function _getAssetName()
    {
        $k = $this->_tbl_key;
        return 'com_userxtd.field.' . (int) $this->$k;
    }
 
    /**
     * Method to return the title to use for the asset table.
     *
     * @return  string 
     *
     * @since   11.1
     */
    protected function _getAssetTitle()
    {
        if( isset($this->title) )
			return $this->title ;
		else
			return $this->_getAssetName() ;
    }
	
	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param	array		Named array
	 * @return	null|string	null is operation was satisfactory, otherwise returns an error
	 * @see		JTable:bind
	 * @since	1.5
	 */
	public function bind($array, $ignore = '')
	{
		// for Fields group
		// Convert jform[fields_group][field] to jform[field] or JTable cannot bind data.
		// ==========================================================================================
		$data = array() ;
		foreach( $array as $val ):
			if(is_array($val)) {
				foreach( $val as $key => $val2 ):
					$array[$key] = $val2 ;
				endforeach;
			}
		endforeach;
		
		
		
		// Set field['param_xxx'] to params
		// ==========================================================================================
		if(empty($array['params'])){
			$array['params'] = array();
		}
		foreach( $array as $key => $row ):
			if( substr($key, 0, 6) == 'param_' && isset($row)){
				$key2 = substr($key, 6) ;
				$array['params'][$key2] = $row  ;
			}
		endforeach;
		
		
		
		// set params
		// ==========================================================================================
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}
		
		
		
		 // Bind the rules.
		 // ==========================================================================================
        if (isset($array['rules']) && is_array($array['rules']))
        {
            $rules = new JAccessRules($array['rules']);
            $this->setRules($rules);
        }
		
		return parent::bind($array, $ignore);
	}
	
	
	
	/*
	 * Setting Nested table, and rebuild.
	 */
	
	public function rebuild($parentId = null, $leftId = 0, $level = 0, $path = '')
	{
		if(!$parentId){
			// If root not exists, create one.
			$table = JTable::getInstance('Field', 'UserxtdTable') ;
			if( !$table->load(1) ){
				$k = $this->_tbl_key;
				
				$table->reset();
				$table->$k = 1 ;
				$table->title = 'ROOT' ;
				$table->alias = 'root' ;
				$table->catid = 1 ;
				
				$table->_db->insertObject( $this->_tbl, $table ) ;
				
				$table->reset() ;
				$table->$k = null ;
			}
			
			
			// If cloumn ordering exists, we need clear it, or nested sorting can't work.
			if(property_exists($this, 'ordering')){
				$db = JFactory::getDbo();
				$q = $db->getQuery(true) ;
				
				$q->update($this->_tbl)
					->set('ordering = 0')
					;
				
				$db->setQuery($q);
				$db->query();
			}
		}
		
		return parent::rebuild($parentId, $leftId, $level, $path);
	}
	
	
	/**
     * Method to store a row in the database from the JTable instance properties.
     * If a primary key value is set the row with that primary key value will be
     * updated with the instance property values.  If no primary key value is set
     * a new row will be inserted into the database with the properties from the
     * JTable instance.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success.
     *
     * @link    http://docs.joomla.org/JTable/store
     * @since   11.1
     */
    public function store($updateNulls = false)
	{
		$db = JFactory::getDbo();
		$q = $db->getQuery(true) ;
		
		$q->select("name")
			->from($this->_tbl)
			->where("name='{$this->name}'")
			->where("id != {$this->id}")
			;
		$db->setQuery($q);
		$result = $db->loadResult();
		
		if($result) {
			$e = new JException(JText::_('COM_USERXTD_FIELD_NAME_EXISTS'));
			$this->setError($e);
			return false;
		}
		
		return parent::store($updateNulls);
	}
}
