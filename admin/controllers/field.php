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


include_once AKPATH_COMPONENT.'/controllerform.php' ;

/**
 * Field controller class.
 */
class UserxtdControllerField extends AKControllerForm
{
	
	public $view_list = 'fields' ;
	public $view_item = 'field' ;
	public $component = 'userxtd';

	
	/**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     JController
     * @since   11.1
     */
	
    function __construct() {
		
		$this->allow_url_params = array(
			'return', 'field_type'
		);
		
		$this->redirect_tasks = array(
			'save', 'cancel', 'publish', 'unpublish', 'delete'
		);
		
        parent::__construct();
    }
	
	
	
	/**
     * Method to save a record.
     *
     * @param   string  $key     The name of the primary key of the URL variable.
     * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
     *
     * @return  boolean  True if successful, false otherwise.
     *
     * @since   11.1
     */
    public function save($key = null, $urlVar = null)
	{
		$result = parent::save($key, $urlVar) ;
		$app = JFactory::getApplication() ;
		
		if(!$result) {
			$context = "{$this->option}.edit.field.fields";
			$attrs = JRequest::getVar('attrs');
			$app->setUserState($context , $attrs );
		}
		
		return $result;
	}
	
	
	
	/**
     * Gets the URL arguments to append to a list redirect.
     *
     * @return  string  The arguments to append to the redirect URL.
     *
     * @since   11.1
     */
	
	protected function getRedirectToListAppend()
	{
		
		foreach( $this->allow_url_params as $param ):
			if($param == 'field_type') continue;
			
			if(JRequest::getVar($param)){
				$append .= "&{$param}=" . JRequest::getVar($param) ;
			}
		endforeach;

		return $append ;
	}

	
	
	/**
     * Function that allows child controller access to model data
     * after the data has been saved.
     *
     * @param   JModel  &$model     The data model object.
     * @param   array   $validData  The validated data.
     *
     * @return  void 
     *
     * @since   11.1
     */
	
	protected function postSaveHook( &$model, $validData = array())
    {
		
    }
	
}