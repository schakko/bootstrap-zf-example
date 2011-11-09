<?php
class CommentControllerTestCase extends Exampleapp_Test_Controller_Base
{
	public function setUp()
	{
		$this->bootstrap = APPLICATION_ENV . "/Bootstrap.php";
	}

	public function testFrontpage()
	{
		$this->setSessionUser("test");
		$this->dispatch('/');
		$this->assertRedirect('/comment');
	}

	public function testIndex()
	{
		$this->dispatch('/comment');

		$this->assertXpathContentContains("//h2", "All comments");
		$this->assertXpathContentContains("//li[@id='2']/p", "Second comment of P. Panzer");
		$this->assertXpathContentContains("//*[@id='2']/strong/span", "2011-11-08 14:51:21");
		$body = $this->getResponse()->getBody();

		// current user (guest) is not allowed to delete any comment
		$this->assertTrue((stristr("Delete this comment", $body) === FALSE));
	}

	public function testAuthenticatedIndex()
	{
		// switch to test user and expect deletion link
		$this->setSessionUser("test");
		$this->dispatch('/comment');
		$this->assertXpathContentContains("//*[@id='9']/a", "Delete this comment");
	}

	public function testCreate()
	{
		// anonymous user is not allowed to do this
		$this->dispatchWithExpectedException("/comment/create", 'Bootstrap_Service_Exception_Authorization');
	}

	public function testValidationCreate()
	{
		$this->setSessionUser('test');

		$this->getRequest()
		->setMethod('POST')
		->setPost(array('comment_type' => '', 'comment' => ''));
		$this->dispatch('/comment/create');
		$this->assertResponseCode(400);
		$body = $this->assertJson();
		// empty validation
		$this->assertTrue(isset($body['errors']['comment_type']['isEmpty']));
		$this->assertTrue(isset($body['errors']['comment']['isEmpty']));

		// out of range validation
		$this->resetRequest()->resetResponse();
		$this->getRequest()
		->setMethod('POST')
		->setPost(array('comment_type' => '2', 'comment' => str_repeat("X", 20)));
		$this->dispatch('/comment/create');
		$body = $this->assertJson();
		$this->assertTrue(isset($body['errors']['comment_type']['notInArray']));
		$this->assertTrue(isset($body['errors']['comment']['stringLengthTooShort']));
	}

	public function testAuthenticatedCreate()
	{
		$this->setSessionUser('test');

		$this->getRequest()
		->setMethod('POST')
		->setPost(array('comment' => str_repeat('X',100), 'comment_type' => 0));
		$this->dispatch('/comment/create');
		$redirectTo = $this->getLocation();
		$this->assertRedirect('/comment');

		$this->resetRequest()->resetResponse();
		$this->dispatch('/comment');

		$body = $this->getResponse()->getBody();
		$this->assertTrue((stristr(str_repeat('X', 100), $body) >= 0));
	}
}
