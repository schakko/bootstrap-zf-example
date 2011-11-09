<?php
class Exampleapp_View_Helper_Username extends Zend_View_Helper_Abstract
{
	private $_router;

	public function setRouter(Zend_Controller_Router_Abstract $router)
	{
		$this->_router = $router;
	}

	/**
	 * converts a given string to a link.
	 * call this helper inside your view with $this->username($username) (method name username is specified inside Bootstrap.php)
	 * @param string
	 * @return string
	 */
	public function username($username)
	{
		return "<a href='" . $this->_router->assemble(array('id' => $username, 'action' => 'details', 'controller' => 'user')) . "'>" . $username . "</a>";
	}
}
