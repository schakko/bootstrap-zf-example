<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	// namespace for user information
	const USER_SESSION_NAMESPACE = 'user';

	// some example roles fro Zend_Acl
	const ROLE_GUEST = 'guest';
	const ROLE_USER = 'user';

	/**
	 * ROLE_GUEST and ROLE_USER inherits from ROLE_PARENT
	 */
	const ROLE_PARENT = 'parent';


	protected function _initResourceLoader()
	{
		// add directory application/service to search path
		$this->_resourceLoader->addResourceType( 'service', 'services', 'Service' );
	}

	protected function _initSession()
	{
		// initialize session
		$session = new Zend_Session_Namespace(self::USER_SESSION_NAMESPACE);

		if (!Zend_Session::namespaceIsset(self::USER_SESSION_NAMESPACE)) {
			// set a dummy object to session if no session exists
			$session->user = new Bootstrap_Model_SessionUser();
		}
	}

	/**
	 * @return Zend_Controller_Router_Abstract
	 */
	private function getRouter()
	{
		$frontController = Zend_Controller_Front::getInstance();
		$router = $frontController->getRouter();
		return $router;
	}

	protected function _initRoutes()
	{
		$router = $this->getRouter();
		
		// load route configuration
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini');
		$router->addConfig($config, 'routes');
		// remove default routes so we can use our own error handling
		$router->removeDefaultRoutes();
		Zend_Controller_Front::getInstance()->setRouter($router);
	}

	protected function _initDoctype()
	{
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();
		$view->doctype('XHTML1_STRICT');
	}

	protected function _initNavigation()
	{
		// load navigation.xml for Zend_Navigation
		$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
		$navigation = new Zend_Navigation($config);
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();
		$view->navigation($navigation);
	}

	protected function _initAcl()
	{
		$acl = new Zend_Acl();

		$acl->addRole(new Zend_Acl_Role(self::ROLE_PARENT));
		$acl->addRole(new Zend_Acl_Role(self::ROLE_GUEST), self::ROLE_PARENT);
		$acl->addRole(new Zend_Acl_Role(self::ROLE_USER), self::ROLE_PARENT);

		// mvc:* are our own defined resources
		$acl->addResource(new Zend_Acl_Resource("mvc:site"));
		$acl->addResource(new Zend_Acl_Resource("mvc:user_logout"));
		$acl->addResource(new Zend_Acl_Resource("mvc:user_login"));
		
		// 'comment' matches to Application_Model_Comment
		$acl->addResource(new Zend_Acl_Resource("comment"));
		
		// 'user' (authorized) is allowed to logout
		$acl->allow(self::ROLE_USER, 'mvc:user_logout');
		// 'parent' (the global role) is allowed to view site elements
		$acl->allow(self::ROLE_PARENT, 'mvc:site', 'navigation');
		// 'guest' (anonymous) is only allowed to login
		$acl->allow(self::ROLE_GUEST, 'mvc:user_login', 'navigation');
		
		// CommentAssertion handles all authorization requests for resource 'comment'
		$commentAssertion = new Exampleapp_Acl_CommentAssertion();
		
		// if 'user' tries to delete a 'comment', Exampleapp_Acl_CommentAssertion handles this request
		$acl->allow(self::ROLE_USER, 'comment', $commentAssertion::DELETE, $commentAssertion);
		// user wants to create a comment:
		$acl->allow(self::ROLE_USER, 'comment', $commentAssertion::CREATE, $commentAssertion);
		// alternatively this works without an assertion class - just for demonstration purposes:
		// $this->allow(self::ROLE_USER, 'comment', 'create');

		Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
		Zend_Registry::set('Zend_Acl', $acl);

		// register action helper for controllers so we can use $this->_helper->acl->authorizeUser() inside a controller
		$aclHelper = new Bootstrap_Controller_Action_Helper_Acl($acl);
		Zend_Controller_Action_HelperBroker::addHelper($aclHelper);
	}


	protected function _initHelper()
	{
		// some funny helpers for the view
		$layout = Zend_Layout::getMvcInstance();

		// Helper_Username converts a username to a link
		$usernameHelper = new Exampleapp_View_Helper_Username();
		$usernameHelper->setRouter($this->getRouter());
		$usernameHelper->setView($layout->getView());
		$layout->getView()->registerHelper($usernameHelper, 'username');

		// Helper_Enum converts an enum (number) to a self describing name
		$enumHelper = new Exampleapp_View_Helper_Enum();
		$enumHelper->setView($layout->getView());
		$layout->getView()->registerHelper($enumHelper, 'enum');
		
		// Is allowed makes Zend_Acl available inside views
		$isAllowedHelper = new Bootstrap_View_Helper_IsAllowed();
		$isAllowedHelper->setAcl(Zend_Registry::get('Zend_Acl'));
		$layout->getView()->registerHelper($isAllowedHelper, 'isAllowed');
	}
}

