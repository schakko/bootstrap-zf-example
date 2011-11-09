<?php
class CommentController extends Bootstrap_Controller_Base
{
	/**
	 *
	 * @var Application_Model_CommentMapper
	 */
	private $_commentMapper;

	public function init()
	{
		// parent is called every time the controller is initiated - so on every request
		parent::init();
		$this->_commentMapper = new Application_Model_CommentMapper();
	}

	/**
	 * view all comments
	 */
	public function indexAction()
	{
		$this->view->comments = $this->_commentMapper->findAll();
	}

	/**
	 * view only my comments
	 */
	public function myAction()
 	{
		$currentUsername = $this->_user->getUser()->username;

		// forward to byuserAction() and use the current username as id parameter
		$this->_forward('byuser', null, null, array('id' => $currentUsername));
	}

	/**
	 * view comments of user
	 */
	public function byuserAction()
	{
		$username = $this->_getParam('id');
		$this->view->username = $username;
		$this->view->comments = $this->_commentMapper->findByUser($username);
		$this->render('byuser');
	}

	/**
	 * create a new comment
	 */
	public function createAction()
	{
		$this->_helper->acl->authorizeUser(new Application_Model_Comment(), 'create', 'You are not allowed to create a new comment');

		if ($this->_request->isPost()) {
			$form = $this->getCreateForm();

			if (!$form->isValid($_POST)) {
				$this->sendJsonFormError($form);
				return;
			}

			$data = new Application_Model_Comment();
			$data->id_user = $this->_user->getId();
			$data->comment = $_POST['comment'];
			$data->comment_type = $_POST['comment_type'];

			$r = $this->_commentMapper->save($data);

			$url = $this->getFrontController()->getRouter()->assemble(array('controller' => 'comment', 'action' => 'index'));
			$this->_redirect($url);
		}
	}

	/**
	 * returns the Zend_Form object for validation of a new comment
	 * @return Zend_Form
	 */
	private function getCreateForm()
	{
		$form = new Zend_Form();
		$form->setAttrib('id', 'formCommentCreate');

		$comment_type = new Zend_Form_Element_Select('comment_type');
		$comment_type->setRequired(true)
		->setMultiOptions(
		array(	Application_Model_Enum_CommentType::THUMBS_UP => 'Thumbs up',
					Application_Model_Enum_CommentType::THUMBS_DOWN => 'Thumbs down'));
		$form->addElement($comment_type);

		$comment = new Zend_Form_Element_Textarea('comment');
		$comment->setRequired(true)
		->addValidator('stringLength', false, 50);
		$form->addElement($comment);

		return $form;
	}

	/**
	 * delete a comment
	 */
	public function deleteAction()
	{
		// extract ID of comment from route comment/delete/:id
		$idComment = $this->_getParam('id');

		// load comment from our database
		$comment = $this->_commentMapper->findById($idComment);

		// if comment does not exist, throw an exception. The exception is catched by ErrorController
		if (!$comment) {
			throw new Application_Service_Exception("This comment does not exist", 404);
		}

		// authorize the current user. If he is not allowed, an exception is thrown
		$this->_helper->acl->authorizeUser($comment, 'delete', 'You are not the owner of this comment and not allowed to delete it');

		// HTTP POST -> execute deletion
		if ($this->getRequest()->isPost()) {
			$this->_commentMapper->delete($idComment);
			$this->_helper->FlashMessenger('Comment was deleted');
			$this->_redirect($_POST['redirect']);
		}

		$this->view->urlBack = $_SERVER['HTTP_REFERER'];
	}
}

