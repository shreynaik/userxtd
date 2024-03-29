<?php
/**
 * @package		Asikart.Plugin
 * @subpackage	system.plg_userxtd
 * @copyright	Copyright (C) 2012 Asikart.com, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Userxtd System Plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	System.userxtd
 * @since		1.5
 */
class plgSystemUserxtd extends JPlugin
{
	
	public static $_self ;
	
	/**
	 * Constructor
	 *
	 * @access      public
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.6
	 */
    public function __construct(&$subject, $config)
    {
		parent::__construct( $subject, $config );
		$this->loadLanguage();
		$this->app = JFactory::getApplication();
		
		// Allow Context
		$this->allow_context = array(
			'com_users.profile', 'com_users.user', 'com_users.registration', 'com_admin.profile',
			'com_userxtd.profile', 'com_userxtd.user', 'com_userxtd.registration'
		);
		
		
		$UXParams = JComponentHelper::getParams('com_userxtd') ;
		$this->params->merge($UXParams) ;
		
		self::$_self = $this ;
    }
	
	
	
	/*
	 * function getInstance
	 */
	
	public static function getInstance()
	{
		return self::$_self ;
	}
	
	
	// System Events
	// ======================================================================================
	
	/**
	* Converting the site URL to fit to the HTTP request
	*
	*/
	function onAfterRoute()
	{
		
		$params = JComponentHelper::getParams('com_userxtd') ;
		
		if($params->get('CoreRegistration_Redirect', 0))	:
		
			$app = JFactory::getApplication() ;
			$option = JRequest::getVar('option') ;
			$view 	= JRequest::getVar('view') ;
			$layout = JRequest::getVar('layout', 'default') ;
			
			if($option == 'com_users') {
				
				if( $view == 'registration' && $layout == 'default' ){
					$app->redirect( JRoute::_('index.php?option=com_userxtd&view=registration') ) ;
				}
				
				
				if( $view == 'profile' && $layout == 'default' ){
					$app->redirect( JRoute::_('index.php?option=com_userxtd&view=user') ) ;
				}
				
				if( $view == 'profile' && $layout == 'edit' ){
					$app->redirect( JRoute::_('index.php?option=com_userxtd&task=user.edit') ) ;
				}
			}
		
		endif;
	}
	
	
	
	
	// Content Events
	// ======================================================================================
	
	
	/**
	 * Userxtd prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The content object.  Note $article->text is also available
	 * @param	object	The content params
	 * @param	int		The 'page' number
	 * @since	1.6
	 */
	public function onContentPrepare($context, &$article, &$params, $page=0)
	{
		$app = JFactory::getApplication();
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
	}
	
	
	/**
	 * Userxtd after display title method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param	string		The context for the content passed to the plugin.
	 * @param	object		The content object.  Note $article->text is also available
	 * @param	object		The content params
	 * @param	int			The 'page' number
	 * @return	string
	 * @since	1.6
	 */
	public function onContentAfterTitle($context, &$article, &$params, $page=0)
	{
		$app 	= JFactory::getApplication();
		$result = null ;
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $result ;
	}
	
	
	/**
	 * Userxtd before display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param	string		The context for the content passed to the plugin.
	 * @param	object		The content object.  Note $article->text is also available
	 * @param	object		The content params
	 * @param	int			The 'page' number
	 * @return	string
	 * @since	1.6
	 */
	public function onContentBeforeDisplay($context, &$article, &$params, $page=0)
	{
		$app 	= JFactory::getApplication();
		$result = null ;
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $result ;
	}
	

	/**
	 * Userxtd after display content method
	 *
	 * Method is called by the view and the results are imploded and displayed in a placeholder
	 *
	 * @param	string		The context for the content passed to the plugin.
	 * @param	object		The content object.  Note $article->text is also available
	 * @param	object		The content params
	 * @param	int			The 'page' number
	 * @return	string
	 * @since	1.6
	 */
	public function onContentAfterDisplay($context, &$article, &$params, $page=0)
	{
		$app 	= JFactory::getApplication();
		$result = null ;
		
		if( $this->params->get('UserInfo', 1) ){
			$result = $this->call('content.userInfo', $context, $article, $params) ;
		}
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $result ;
	}
	
	
	/**
	 * Userxtd before save content method
	 *
	 * Method is called right before content is saved into the database.
	 * Article object is passed by reference, so any changes will be saved!
	 * NOTE:  Returning false will abort the save with an error.
	 *You can set the error by calling $article->setError($message)
	 *
	 * @param	string		The context of the content passed to the plugin.
	 * @param	object		A JTableContent object
	 * @param	bool		If the content is just about to be created
	 * @return	bool		If false, abort the save
	 * @since	1.6
	 */
	public function onContentBeforeSave($context, &$article, $isNew)
	{
		$app 	= JFactory::getApplication();
		$result = array() ;
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $this->resultBool($result);
	}
	
	
	/**
	 * Userxtd after save content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved
	 *
	 * @param	string		The context of the content passed to the plugin (added in 1.6)
	 * @param	object		A JTableContent object
	 * @param	bool		If the content is just about to be created
	 * @since	1.6
	 */
	public function onContentAfterSave($context, &$article, $isNew)
	{
		$app 	= JFactory::getApplication();
		$result = array() ;
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $this->resultBool($result);
	}
	

	/**
	 * Userxtd before delete method.
	 *
	 * @param	string	The context for the content passed to the plugin.
	 * @param	object	The data relating to the content that is to be deleted.
	 * @return	boolean
	 * @since	1.6
	 */
	public function onContentBeforeDelete($context, $data)
	{
		$result = array() ;
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $this->resultBool($result);
	}
	
	
	/**
	 * Userxtd after delete method.
	 *
	 * @param	string	The context for the content passed to the plugin.
	 * @param	object	The data relating to the content that was deleted.
	 * @return	boolean
	 * @since	1.6
	 */
	public function onContentAfterDelete($context, $data)
	{
		$result = array() ;
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $this->resultBool($result);
	}
	

	/**
	 * Userxtd after delete method.
	 *
	 * @param	string	The context for the content passed to the plugin.
	 * @param	array	A list of primary key ids of the content that has changed state.
	 * @param	int		The value of the state that the content has been changed to.
	 * @return	boolean
	 * @since	1.6
	 */
	public function onContentChangeState($context, $pks, $value)
	{
		$result = array() ;
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $this->resultBool($result);
	}
	
	
	
	// Form Events
	// ====================================================================================
	
	
	/**
	 * @param	JForm	$form	The form to be altered.
	 * @param	array	$data	The associated data for the form.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	public function onContentPrepareForm($form, $data)
	{
        $app = JFactory::getApplication() ;
        
        // Check we are manipulating a valid form.
		$name = $form->getName();
		if (!in_array($name, $this->allow_context))
		{
			return true;
		}
        
        // Include UserXTD core API.
		include_once JPATH_ADMINISTRATOR.'/components/com_userxtd/includes/core.php' ;
		JForm::addFieldPath(AKPATH_FORM.'/fields');
		
		
		$result = null ;
		$db 	= JFactory::getDbo();
		$q 		= $db->getQuery(true) ;
		$UXParams= UserxtdHelper::_('getParams', 'com_userxtd');
		UXHelper::_('include.sortedStyle', 'includes/css', 'com_userxtd' );
		
		
		// Prepare Data
		// ============================================================================
		
		// Set Chosen
		if(JVERSION >= 3) {
			JHtml::_('formbehavior.chosen', 'select');
		}
		
		
		// Prepare Form
		// ============================================================================
		
		// Get Form
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}
		
		// Hide some fields in registration
		$context = $this->getContext() ;
		$array 	= array();
		$reg_context     = array( 'com_users.registration', 'com_userxtd.registration' );
		$profile_context = array( 'com_users.profile');
		
		$catid = null;
		if( in_array($context, $reg_context) || $this->get('hide_registration_field'))
		{
			$array['hide_in_registration'] = true ;
			
			$catid = $UXParams->get('CoreRegistration_Categories', array('*'));
		}
		elseif(in_array($context, $profile_context))
		{
			$catid = $UXParams->get('CoreRegistration_Categories_InUserInfo', array('*'));
		}
		
		// Set category
		if(!is_array($catid)) {
			$catid = array($catid);
		}
		
		if(!in_array('*', $catid)) {
			$catid = implode(',', $catid);
		}else{
			$catid = null ;
		}
		
		
		$form = UXHelper::_('form.getFieldsByCategory', $catid, $form, $array);
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $result;
	}
	
	
	
	// User Events
	// ====================================================================================
	
	
	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 *
	 * @param	array		$user		Holds the new user data.
	 * @param	boolean		$isnew		True if a new user is stored.
	 * @param	boolean		$success	True if user was succesfully stored in the database.
	 * @param	string		$msg		Message.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function onUserBeforeSave($user, $isnew, $success, $msg)
	{
		$result = array() ;
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $this->resultBool($result);
	}
	
	
	/**
	 * Utility method to act on a user after it has been saved.
	 *
	 *
	 * @param	array		$user		Holds the new user data.
	 * @param	boolean		$isnew		True if a new user is stored.
	 * @param	boolean		$success	True if user was succesfully stored in the database.
	 * @param	string		$msg		Message.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function onUserAfterSave($data, $isNew, $result, $error)
	{
		//$result = array() ;
		$input  = JFactory::getApplication()->input ;
		$db 	= JFactory::getDbo();
		
		// don't do anything when activate.
		if($input->get('task') == 'activate')
		{
			return true;
		}
		
		// Init Framework
		// ===============================================================
		include_once JPATH_ADMINISTRATOR.'/components/com_userxtd/includes/core.php' ;
		include_once AKPATH_FORM.'/form.php' ;
		$UXParams = UserxtdHelper::_('getParams');
		
		// For Upload Event
		$this->user_data = $data ;
		
		
		// Set Category
		$catid = $UXParams->get('CoreRegistration_Categories', array('*'));
		
		if(!is_array($catid)) {
			$catid = array($catid);
		}
		
		if(!in_array('*', $catid)) {
			$catid = implode(',', $catid);
		}else{
			$catid = null ;
		}
		
		
		// Get Data and handle them
		$form 	= UXHelper::_('form.getFieldsByCategory', $catid);
		$form->bind($data);
		$data['profile'] = $form->getDataForSave('profile');
		$userId	= JArrayHelper::getValue($data, 'id', 0, 'int');
		
		
		
		// Start Building query
		// ===============================================================
		if ($userId && $result && isset($data['profile']) && (count($data['profile'])))
		{
			try
			{
				//Sanitize the date
				// ===============================================================
				if (!empty($data['profile']['dob']))
				{
					$date = new JDate($data['profile']['dob']);
					$data['profile']['dob'] = $date->format('Y-m-d');
				}
				
				$q = $db->getQuery(true) ;
				$q->delete('#__userxtd_profiles')
					->where('user_id = '.$userId)
					;
				
				$db->setQuery($q);
				$db->execute();

				$tuples = array();
				$order	= 1;
				
				$q = $db->getQuery(true) ;
				$q->columns(array($q->qn('user_id'), $q->qn('key'), $q->qn('value'), $q->qn('ordering')));
				
				
				
				// Build query
				// ===============================================================
				foreach ($data['profile'] as $k => $v)
				{
					
					if(is_array($v) || is_object($v)) {
						$v = implode(',', (array)$v ) ;
					}
					
					$q->values($userId.', '.$db->quote($k).', '.$db->quote($v).', '.$order++);
				}
				
				$q->insert('#__userxtd_profiles');
				$db->setQuery($q);
				$db->execute();
				
			}
			catch (RuntimeException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
			
		}
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return true;
	}
	
	
	/*
	 * function onCCKEngineAfterFormLoad
	 * @param &$form , &$data, &$this, &$element, &$form_setted
	 */
	
	public function onCCKEngineAfterFormLoad($form = null, $data = null, $formfield = null, $element = null, $form_setted = false)
	{
		// Add Hide reg field
		$form->loadFile( dirname(__FILE__).'/form/forms/fields.xml' );
		
		// label do not required
		$form->setFieldAttribute('label', 'required','false') ;
	}
	
	
	
	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @param	array	$user		Holds the user data
	 * @param	array	$options	Array holding options (remember, autoregister, group)
	 *
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	public function onUserLogin($user, $options = array())
	{
		$result = array() ;
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $this->resultBool($result);
	}
	
	
	/**
	 * This method should handle any logout logic and report back to the subject
	 *
	 * @param	array	$user		Holds the user data.
	 * @param	array	$options	Array holding options (client, ...).
	 *
	 * @return	object	True on success
	 * @since	1.5
	 */
	public function onUserLogout($user, $options = array())
	{
		$result = array() ;
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $this->resultBool($result);
	}
	
	
	/**
	 * Utility method to act on a user before it has been saved.
	 *
	 *
	 * @param	array		$user		Holds the new user data.
	 * @param	boolean		$isnew		True if a new user is stored.
	 * @param	boolean		$success	True if user was succesfully stored in the database.
	 * @param	string		$msg		Message.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function onUserBeforeDelete($user, $isnew, $success, $msg)
	{
		$result = array() ;
		
		// Code here
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $this->resultBool($result);
	}
	
	
	/**
	 * Remove all sessions for the user name
	 *
	 * @param	array		$user	Holds the user data
	 * @param	boolean		$succes	True if user was succesfully stored in the database
	 * @param	string		$msg	Message
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	public function onUserAfterDelete($user, $success, $msg)
	{
		$result = array() ;
				
		if (!$success)
		{
			// return false;
		}

		$userId	= JArrayHelper::getValue($user, 'id', 0, 'int');

		if ($userId)
		{
			try
			{
				$db = JFactory::getDbo();
				$q = $db->getQuery(true) ;
				
				$q->delete('#__userxtd_profiles')
					->where('user_id = '.$userId)
					;
				
				$db->setQuery($q);
				$db->execute();
			}
			catch (Exception $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $this->resultBool($result);
	}
	
	
	/**
	 * @param	string	$context	The context for the data
	 * @param	int		$data		The user id
	 * @param	object
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	public function onContentPrepareData($context, $data)
	{
		$result = array() ;
		$params = JComponentHelper::getParams('com_userxtd') ;
		include_once JPATH_ADMINISTRATOR.'/components/com_userxtd/helpers/fieldshow.php' ;
		JHtml::register('users.uploadimage', array('UserxtdHelperFieldshow', 'showImage'));
		
		// Check we are manipulating a valid form.	
		if (!in_array($context, $this->allow_context))
		{
			return true;
		}
		
		if (is_object($data))
		{
			$userId = isset($data->id) ? $data->id : 0;

			if (!isset($data->profile) and $userId > 0)
			{
				// Load the profile data from the database.
				// ===============================================================
				$db = JFactory::getDbo();
				$q = $db->getQuery(true) ;
				
				// Filter categories
				$cats = (array) $params->get('CoreRegistration_Categories_InUserInfo', array('*'));
				
				if(!in_array('*', $cats))
				{
					$q->where('b.catid IN (' . implode(',', $cats) . ')');
				}
				
				$q->select("a.key, a.value, b.field_type, b.attrs")
					->from("#__userxtd_profiles AS a")
					->leftJoin('#__userxtd_fields AS b ON a.key=b.name')
					->where('a.user_id = '.(int) $userId)
					->order("b.ordering")
					;
				
				$db->setQuery($q);

				try
				{
					$results = $db->loadRowList();
				}
				catch (RuntimeException $e)
				{
					$this->_subject->setError($e->getMessage());
					return false;
				}
				
				
				
				// Merge the profile data.
				// ===============================================================
				$data->profile = array();

				foreach ($results as $v)
				{
					$k = $v[0];
					
					
					// Convert String to Array if multiple
					$v[3] = json_decode($v[3], true);
					if( JArrayHelper::getValue($v[3], 'multiple') ) {
						$v[1] = explode(',', $v[1]);
					}
					
					// merge data
					$data->profile[$k] = $v[1];
					if ($data->profile[$k] === null)
					{
						$data->profile[$k] = $v[1];
					}
				}
			}
		}
		
		$result[] = true ;
		
		
		if( $path = $this->includeEvent(__FUNCTION__) ) @include $this->includeEvent(__FUNCTION__);
		
		return $this->resultBool($result);
	}
	
	
	
	/*
	 * function onCCKEngineUploadImage
	 * @param $url
	 */
	
	public function onCCKEngineUploadImage($url, $field, $element = null)
	{
		$data 	= $this->user_data ;
		
		$name 	= $_FILES['jform']['name']['profile'][$field->fieldname . '_upload'] ;
		$name	= explode('.', $name) ;
		$ext	= array_pop( $name );
		
		if($data && $url) {
			$url = "images/userxtd/{$data['username']}/" . $field->fieldname . '.' . $ext;
		}
		
	}
	
	
	
	// AKFramework Functions
	// ====================================================================================
	
	
	/**
	 * function call
	 * 
	 * A proxy to call class and functions
	 * Example: $this->call('folder1.folder2.function', $args) ; OR $this->call('folder1.folder2.Class::function', $args)
	 * 
	 * @param	string	$uri	The class or function file path.
	 * 
	 */
	
	public function call( $uri ) {
		// Split paths
		$path = explode( '.' , $uri );
		$func = array_pop($path);
		$func = explode( '::', $func );
		$class_name = null ;
		
		// set class name of function name.
		if(isset($func[1])){
			$class_name = $func[0] ;
			$func_name = $func[1] ;
			$file_name = $class_name ;
		}else{
			$func_name = $func[0] ;
			$file_name = $func_name ;
		}
		
		$func_path 		= implode('/', $path).'/'.$file_name;
		$include_path = JPATH_ROOT.'/'.$this->params->get('include_path', 'userxtd');
		
		// include file.
		if( !function_exists ( $func_name )  && !class_exists($class_name) ) :			
			$file = trim($include_path, '/').'/'.$func_path.'.php' ;
			
			if( !file_exists($file) ) {
				$file = dirname(__FILE__).'/lib/'.$func_path.'.php' ;
			}
			
			if( file_exists($file) ) {
				include_once( $file ) ;
			}
		endif;
		
		// Handle args
		$args = func_get_args();
        array_shift( $args );
        
		// Call Function
		if(isset($class_name) && method_exists( $class_name, $func_name )){
			return call_user_func_array( array( $class_name, $func_name ) , $args );
		}elseif(function_exists ( $func_name )){
			return call_user_func_array( $func_name , $args );
		}
		
	}
	
	
	
	public function includeEvent($func) {
		$include_path = JPATH_ROOT.'/'.$this->params->get('include_path', 'userxtd');
		$event = trim($include_path, '/').'/'.'events/'.$func.'.php' ;
		if(file_exists( $event )) return $event ;
	}
	
	
	
	public function resultBool($result = array()) {
		foreach( $result as $result ):
			if(!$result) return false ;
		endforeach;
		
		return true ;
	}
	
	
	/*
	 * function getContext
	 * @param 
	 */
	
	public function getContext()
	{
		$option 	= JRequest::getVar('option') ;
		$view 		= JRequest::getVar('view') ;
		$context 	= "{$option}.{$view}" ;
		
		return $context ;
	}
}
