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
 
/**
 * Script file of HelloWorld component
 */
class com_UserxtdInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		
		$cats 	= JFile::read(JPATH_ADMINISTRATOR.'/components/com_userxtd/sql/example_categories.json') ;
		$basic 	= JFile::read(JPATH_ADMINISTRATOR.'/components/com_userxtd/sql/example_basic.json') ;
		$living = JFile::read(JPATH_ADMINISTRATOR.'/components/com_userxtd/sql/example_living.json') ;
		
		$db 	= JFactory::getDbo();
		$user 	= JFactory::getUser() ;
		$date 	= JFactory::getDate( 'now' , JFactory::getConfig()->get('offset') ) ;
		$cats 	= json_decode($cats, true) ;
		$catTable = JTable::getInstance('Category') ;
		
		$catids = array();
		
		
		// Import Categories
		foreach( $cats as $cat ):
			$catTable->bind($cat) ;
			$catTable->id 		= null ;
			$catTable->alias 	= 'userxtd-'.$catTable->alias ;
			$catTable->asset_id = null ;
			$catTable->lft 		= null ;
			$catTable->rgt 		= null ;
			$catTable->parent_id = 1 ;
			$catTable->created_user_id 	= $user->id ;
			$catTable->modified_user_id = $user->id ;
			$catTable->created_time 	= $date->toSQL(true);
			$catTable->modified_time 	= $db->getNullDate();
			$catTable->setLocation(1, 'last-child');
			$catTable->store();
			$catids[] = $catTable->id ;
			
			$catTable->reset();
		endforeach;
		
		
		// Import Basic Fields
		$fields = json_decode($basic, true) ;
		
		include_once JPATH_ADMINISTRATOR.'/components/com_userxtd/tables/field.php' ;
		$fieldTable = JTable::getInstance('Field', 'UserxtdTable');
		foreach( $fields as $field ):
			$fieldTable->bind($field) ;
			$fieldTable->id 		= null ;
			$fieldTable->asset_id 	= null ;
			$fieldTable->catid 		= $catids[0] ;
			$fieldTable->created_by = $user->id ;
			$fieldTable->modified_by= $user->id ;
			$fieldTable->created 	= $date->toSQL(true);
			$fieldTable->store();
			$fieldTable->reset();
		endforeach;
		
		
		// Import Living Fields
		$fields = json_decode($living, true) ;
		
		$fieldTable = JTable::getInstance('Field', 'UserxtdTable');
		foreach( $fields as $field ):
			$fieldTable->bind($field) ;
			$fieldTable->id 		= null ;
			$fieldTable->asset_id 	= null ;
			$fieldTable->catid 		= $catids[1] ;
			$fieldTable->created_by = $user->id ;
			$fieldTable->modified_by= $user->id ;
			$fieldTable->created 	= $date->toSQL(true);
			$fieldTable->store();
			
			$fieldTable->reset();
		endforeach;
		
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		
		$db = JFactory::getDbo();
		
		
		// Get install manifest
		// ========================================================================
		$p_installer 	= $parent->getParent() ;
		$installer 		= new JInstaller();
		$manifest 		= $p_installer->manifest ;
		$path 			= $p_installer->getPath('source');
		$result			= array() ;
		
		$css =
<<<CSS
	<style type="text/css">
		#ak-install-img {
			
		}
		
		#ak-install-msg {
			
		}
	</style>
CSS;
		
		echo $css ;
		include_once $path.'/windwalker/admin/installscript.php' ;
	}
	
}