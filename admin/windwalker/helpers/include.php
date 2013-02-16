<?php
/**
 * @package     Windwalker.Framework
 * @subpackage  AKHelper
 *
 * @copyright   Copyright (C) 2012 Asikart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Generated by AKHelper - http://asikart.com
 */

 
// no direct access
defined('_JEXEC') or die;


class AKHelperInclude
{
	static $bootstrap ;
	static $bluestork ;
	
	
	/*
	 * function dropdownCheckbox
	 * @param $framework
	 */
	
	public static function dropdownCheckbox($id = 'MultiSelect', $framework = true)
	{
		if($framework) {
			JHtml::_('behavior.framework', true) ;
		}
		
		$doc = JFactory::getDocument();
		$script = AKHelper::_('path.getWWUrl').'/assets/js/mootools/multi-select/source/' . (JDEBUG ? 'MultiSelect-uncompressed.js' : 'MultiSelect.js') ;
		$css	= AKHelper::_('path.getWWUrl').'/assets/js/mootools/multi-select/css/MultiSelect.css';
		
		$doc->addScript( $script );
		$doc->addStylesheet( $css );
		
		$instance = <<<JS
		window.addEvent('domready', function(){
			var AKMultiSelect_{$id} = new MultiSelect('.{$id}');
		});
JS;
		
		$doc->addScriptDeclaration($instance);
	}
	
	
	
	/*
	 * function core
	 * @param 
	 */
	
	public static function core($js = true)
	{
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication() ;
		$option = JRequest::getVar('option') ;
		$com_name = str_replace('com_', '', $option) ;
		
		$prefix = $app->isAdmin() ? '../' : '' ;
		
		JHtml::_('stylesheet', $prefix.'components/'.$option.'/includes/css/'.$com_name.'-core.css');
		JHtml::_('stylesheet', $prefix.'components/'.$option.'/includes/css/'.$com_name.'.css');
		
		if($js){
			JHtml::_('behavior.framework', true);
			JHtml::_('script', $prefix.'components/'.$option.'/includes/js/'.$com_name.'-core.js', true);
			JHtml::_('script', $prefix.'components/'.$option.'/includes/js/'.$com_name.'.js', true);
		}
	}
	

	
	/*
	 * function bootstrap
	 * @param 
	 */
	
	public static function bootstrap($responsive = false, $js = true)
	{	
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication() ;
		$option = JRequest::getVar('option') ;
		
		$prefix = $app->isSite() ? 'administrator/' : '' ;
		$min	= JDEBUG ? '.min' : '' ;
		
		if(JVERSION < 3){
			JHtml::_('stylesheet', $prefix.'components/'.$option.'/includes/bootstrap/css/bootstrap'.$min.'.css');
		}else{
			JHtml::_('bootstrap.loadCss') ;
		}
		
		if($responsive && JVERSION < 3 ){
			JHtml::_('stylesheet', $prefix.'components/'.$option.'/includes/bootstrap/css/bootstrap-responsive'.$min.'.css');
		}
		
		if( $js && JVERSION < 3 ){
			JHtml::_('script', $prefix.'components/'.$option.'/includes/bootstrap/js/jquery.js');
			JHtml::_('script', $prefix.'components/'.$option.'/includes/bootstrap/js/bootstrap'.$min.'.js');
			
			if( JVERSION < 3 ) {
				$doc->addScriptDeclaration("jQuery.noConflict();") ;
			}
		}else{
			JHtml::_('jquery.framework', true ,JDEBUG) ;
			JHtml::_('bootstrap.framework', JDEBUG) ;
		}
		
		self::$bootstrap = true ;
	}
	
	
	/*
	 * function bluestork
	 * @param 
	 */
	
	public static function bluestork()
	{
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication() ;
		$option = JRequest::getVar('option') ;
		
		$prefix = $app->isSite() ? 'administrator/' : '' ;
		
		$doc->addStylesheet($prefix.'templates/bluestork/css/template.css');
		
		self::$bluestork = true ;
	}
	
	
	/*
	 * function isis
	 * @param 
	 */
	
	public static function isis()
	{
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication() ;
		$option = JRequest::getVar('option') ;
		
		$prefix = $app->isSite() ? 'administrator/' : '' ;
		
		$doc->addStylesheet($prefix.'templates/isis/css/template.css');
		
		self::$bluestork = true ;
	}
	
	
	/*
	 * function fixBluestorkAndBootstrap
	 * @param 
	 */
	
	public static function fixBootstrapToJoomla()
	{
		$option = JRequest::getVar('option') ;
		JHtml::_('stylesheet', 'components/'.$option.'/includes/css/fix-bootstrap-to-joomla.css');
		
		JHtml::_('behavior.framework', true);
		JHtml::_('script', 'components/'.$option.'/includes/js/fix-bootstrap-to-joomla.js', true);
	}
	
	
	/*
	 * function includeStyleByNumber
	 * @param $path
	 */
	
	public static function sortedStyle($path = null, $client = null)
	{
		$files 	= JFolder::files(AKHelper::_('path.get', $client).'/'.$path, ".css$", true);
		$doc 	= JFactory::getDocument();
		$option = JRequest::getVar('option') ;
		
		foreach( $files as $key => $file ):
			$name = explode('-', $file) ;
			if( is_numeric( array_shift( $name ) ) ) {
				$doc->addStylesheet( AKHelper::_('uri.component').'/'.$path.'/'.$file ) ;
			}
		endforeach;
	}
	
}