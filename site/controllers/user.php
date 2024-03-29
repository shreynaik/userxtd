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
 * User controller class.
 */
class UserxtdControllerUser extends AKControllerForm
{
	
	public $view_list = 'users' ;
	public $view_item = 'user' ;
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
			'return'
		);
		
		$this->redirect_tasks = array(
			'save', 'cancel', 'publish', 'unpublish', 'delete'
		);
		
        parent::__construct();
    }

	
	/**
	 * Method to check out a user for editing and redirect to the edit form.
	 *
	 * @since   1.6
	 */
	public function edit()
	{
		$app			= JFactory::getApplication();
		$user			= JFactory::getUser();
		$loginUserId	= (int) $user->get('id');

		// Get the previous user id (if any) and the current user id.
		$previousId = (int) $app->getUserState('com_userxtd.edit.profile.id');
		$userId = JRequest::getVar('id') ;

		// Check if the user is trying to edit another users profile.
		if ($userId != $loginUserId && !$user->authorise('core.manage'))
		{
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_userxtd.edit.profile.id', $userId);

		// Get the model.
		$model = $this->getModel('User', 'UserxtdModel', array('ignore_request' => false));

		// Check out the user.
		if ($userId)
		{
			$model->checkout($userId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_userxtd&view=user&layout=edit'.$this->getRedirectToItemAppend(), false));
	}
	
	
	
	/**
	 * Method to save a user's profile data.
	 *
	 * @return  void
	 * @since   1.6
	 */
	public function save()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app	= JFactory::getApplication();
		$model	= $this->getModel('User', 'UserxtdModel', array('ignore_request' => false));
		$jform 	= JRequest::getVar('jform') ;
		$userId	= $jform['id'] ;
		$user	= JFactory::getUser($userId);

		// Get the user data.
		$data = $app->input->post->get('jform', array(), 'array');

		// Force the ID to this user.
		$data['id'] = $userId;

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form)
		{
			JError::raiseError(500, $model->getError());
			return false;
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_userxtd.edit.profile.data', $data);

			// Redirect back to the edit screen.
			$userId = (int) $app->getUserState('com_userxtd.edit.profile.id');
			$this->setRedirect(JRoute::_('index.php?option=com_userxtd&task=user&layout=edit&id='.$userId.$this->getRedirectToItemAppend(), false));
			return false;
		}

		// Attempt to save the data.
		$return	= $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_userxtd.edit.profile.data', $data);

			// Redirect back to the edit screen.
			$userId = (int) $app->getUserState('com_userxtd.edit.profile.id');
			$this->setMessage(JText::sprintf('COM_USERS_PROFILE_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_userxtd&view=user&layout=edit&id='.$userId.$this->getRedirectToItemAppend(), false));
			return false;
		}

		// Redirect the user and adjust session state based on the chosen task.
		switch ($this->getTask())
		{
			case 'apply':
				// Check out the profile.
				$app->setUserState('com_userxtd.edit.profile.id', $return);
				$model->checkout($return);

				// Redirect back to the edit screen.
				$this->setMessage(JText::_('COM_USERS_PROFILE_SAVE_SUCCESS'));
				$this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_userxtd.edit.profile.redirect')) ? $redirect : 'index.php?option=com_userxtd&view=user&layout=edit&hidemainmenu=1'.$this->getRedirectToItemAppend(), false));
				break;

			default:
				// Check in the profile.
				$userId = (int) $app->getUserState('com_userxtd.edit.profile.id');
				if ($userId)
				{
					$model->checkin($userId);
				}

				// Clear the profile id from the session.
				$app->setUserState('com_userxtd.edit.profile.id', null);

				// Redirect to the list screen.
				$this->setMessage(JText::_('COM_USERS_PROFILE_SAVE_SUCCESS'));
				$this->setRedirect(JRoute::_(($redirect = $app->getUserState('com_userxtd.edit.profile.redirect')) ? $redirect : 'index.php?option=com_userxtd&view=user&id='.$return.$this->getRedirectToItemAppend(), false));
				break;
		}

		// Flush the data from the session.
		$app->setUserState('com_userxtd.edit.profile.data', null);
	}
	
	
	
	/*
	 * function canael
	 * @param 
	 */
	
	public function cancel($key = null)
	{
		$r = parent::cancel($key);
		
		$app = JFactory::getApplication() ;
		
		$this->setRedirect (
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_item
				. '&id=' . $app->getUserState('com_userxtd.edit.profile.id')
				. $this->getRedirectToListAppend(), false
			)
		);

		return true;
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