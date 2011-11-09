<?php
class IndexController extends Bootstrap_Controller_Base
{
	public function init()
	{
		parent::init();
	}

	public function indexAction()
	{
		// index action redirects to CommentController::index
		$redirectUrl = $this->getFrontController()->getRouter()->assemble(array('controller' => 'comment'));
		$this->_redirect($redirectUrl);
	}

	/**
	 * returns jquery-templates, if desired
	 */
	public function templatesAction()
	{
		// disable layout - we only need the template
		$this->_helper->layout->disableLayout();
		$this->render('jquery-templates');
	}
}

