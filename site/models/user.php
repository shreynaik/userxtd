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

include_once AKPATH_COMPONENT.'/modeladmin.php' ;

/**
 * Userxtd model.
 */
class UserxtdModelUser extends AKModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected 	$text_prefix = 'COM_USERXTD';
	
	public 		$component = 'userxtd' ;
	public 		$item_name = 'user' ;
	public 		$list_name = 'users' ;
	
	
	
	/*
	 * function getTable
	 * @param $name
	 */
	
	public function getTable($name = 'User', $prefix = 'JTable', $option = array())
	{
		return parent::getTable($name, $prefix, $option);
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
		$form = parent::getForm($data, $loadData) ;
		
		return $form ;
	}
	
	
	/*
	 * function getFields
	 * @param 
	 */
	
	public function getFields()
	{
		$fields = parent::getFields();
		
		return $fields ;
	}
	
	

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		$data = parent::loadFormData();
		
		return $data ;
	}

	
	
	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getUser($pk = null)
	{
		$id 	= JRequest::getVar('id') ;
		$user 	= UXFactory::getUser($id);
		
		if($user) {
			
			
			return $user ;
		}

		return false;
	}
	
	
	
	/*
	 * function getFields
	 * @param 
	 */
	
	public function getProfiles()
	{
		$catids = $this->getState('params')->get('CoreRegistration_Categories') ;
		
		if(in_array('*', $catids)) {
			$catids = null ;
		}
		
		$fields = UXHelper::_('form.getFieldsByCategory', $catids) ;
		
		return $fields ;
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
		return parent::preprocessForm($form, $data, $group);
	}
	

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable($table)
	{
		return parent::prepareTable($table);
	}
	
}