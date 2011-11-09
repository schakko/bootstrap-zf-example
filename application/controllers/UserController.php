<?php
class UserController extends Bootstrap_Controller_Base
{
	/**
	 *
	 * @var Application_Model_UserMapper
	 */
	private $_userMapper;

	public function init()
	{
		parent::init();
		$this->_userMapper = new Application_Model_UserMapper();
	}

	public function loginAction()
	{
		if ($this->_request->isPost()) {
			// client can pass form name so JavaScript can decide whether normal form or quick bar form has been used
			$formName = "formLogin";

			if (isset($_POST['formName'])) {
				$formName = $_POST['formName'];
			}

			$form = $this->getForm($formName);
			$form->isValid($_POST);

			$user = $this->_userMapper->findByUsername($this->_getParam('username'));

			// add errors after validation
			if ($user == null) {
				if (!$form->getElement('username')->hasErrors()) {
					$form->getElement('username')->addError('Given user could not be found');
				}
			}
			else {
				if (!$form->getElement('password')->hasErrors()
				&& ($user->password !== hash("sha256", $this->_getParam('password')))) {
					$form->getElement('password')->addError('Invalid password');
				}
			}

			// some errors occured -> send error messages
			if (sizeof($form->getMessages()) > 0) {
				$this->sendJsonFormError($form);
				return;
			}

			// update session information with current user object and Zend_Acl-role
			$this->_user->setUser($user);
			$this->_user->setRoleId('user');

			// redirect to own comment site
			$redirectUrl = $this->getFrontController()->getRouter()->assemble(array('controller' => 'comment', 'action' => 'my'), 'default');
			$this->sendJsonResponse(array('redirect_to' => $redirectUrl));
		}
	}

	private function getForm($formName)
	{
		$form  = new Zend_Form();
		$form->setName($formName);
		$form->setAttrib('id', $formName);
		$username = new Zend_Form_Element_Text('username');
		$username->setRequired(true);
		$form->addElement($username);

		$password = new Zend_Form_Element_Password('password');
		$password->setRequired(true);
		$form->addElement($password);

		return $form;
	}

	public function logoutAction()
	{
		Zend_Session::destroy(true);
		$this->_redirect('/');
	}

	public function detailsAction()
	{
		$this->view->user = $this->_userMapper->findByUsername($this->_getParam('id'));

		if (!$this->view->user) {
			throw new Application_Service_Exception("Given user could not be found", 404);
		}
	}
}
