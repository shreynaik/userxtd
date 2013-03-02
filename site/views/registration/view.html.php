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

include_once AKPATH_COMPONENT.'/viewitem.php' ;

/**
 * View class for a list of Userxtd.
 */
class UserxtdViewRegistration extends AKViewItem
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected 	$text_prefix = 'COM_USERXTD';
	protected 	$items;
	protected 	$pagination;
	protected 	$state;
	
	public		$option 	= 'com_userxtd' ;
	public		$list_name 	= 'registrations' ;
	public		$item_name 	= 'registration' ;
	public		$sort_fields ;
	
	

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$app 	= JFactory::getApplication() ;
		$user 	= JFactory::getUser() ;
		
		$this->state	= $this->get('State');
		$this->params	= $this->state->get('params');
		$this->form		= $this->get('Form');
		$this->canDo	= UserxtdHelper::getActions();
		
		$layout = $this->getLayout();
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		if( $layout == 'edit' ) {
			$this->form	= $this->get('Form');
			
			parent::displayWithPanel($tpl);
			return true ; 
		}
		
		
		parent::display($tpl);
	}
	
	
	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		AKToolBarHelper::title( 'Registration' . ' ' . JText::_('COM_USERXTD_TITLE_ITEM_EDIT'), 'article-add.png');

		parent::addToolbar();
	}
	
	
	
	/*
	 * function handleFields
	 * @param 
	 */
	
	public function handleFields()
	{
		$form = $this->form ;
		
		parent::handleFields();
		
		// for Joomla! 3.0
		if(JVERSION >= 3) {
			
			// $form->removeField('name', 'fields');
			
		}else{
			
			// $form->removeField('name', 'fields');
			
		}
		
	}
}
