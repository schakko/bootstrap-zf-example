<?php
abstract class Exampleapp_Test_Controller_Base extends Bootstrap_Test_Controller_Base
{
	public function setUp()
	{
		$this->bootstrap = APPLICATION_ENV . "/Bootstrap.php";
		parent::setUp();
	}

	public function tearDown()
	{
		parent::tearDown();
	}

	/**
	 * Does a context switch so we can execute the assertions under different users
	 * @param string username
	 */
	protected function setSessionUser($username)
	{
		$session = new Zend_Session_Namespace('user');
		$sessionUser = new Bootstrap_Model_SessionUser();
		$userMapper = new Application_Model_UserMapper();
		$user = $userMapper->findByUsername($username);
		$this->assertNotNull($user);
		$this->assertEquals($username, $user->username);

		$sessionUser->setRoleId('user');
		$sessionUser->setUser($user);
		$session->user = $sessionUser;
	}
}
