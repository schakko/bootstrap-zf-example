<?php
class UserControllerTestCase extends Exampleapp_Test_Controller_Base
{
	public function testLoginValidation()
	{
		$this->getRequest()->setMethod('POST');
		$this->dispatch('/login');
		$this->assertResponseCode(400);
		$body = $this->assertJson();
		$this->assertEquals('formLogin', $body['id']);
		$this->assertTrue(isset($body['errors']['username']['isEmpty']));
		$this->assertTrue(isset($body['errors']['password']['isEmpty']));
	}

	public function testWrongLoginCredentials()
	{
		$this->getRequest()
		->setMethod('POST')
		->setPost(array('username' => 'invalid', 'password' => 'invalid'));
		$this->dispatch('/login');
		$this->assertResponseCode(400);
		$body = $this->assertJson();
		$this->assertTrue(isset($body['errors']['username']));

		$this->getRequest()
		->setMethod('POST')
		->setPost(array('username' => 'test', 'password' => 'invalid'));
		$this->dispatch('/login');
		$this->assertResponseCode(400);
		$body = $this->assertJson();
		$this->assertFalse(isset($body['errors']['username']));
		$this->assertTrue(isset($body['errors']['password']));
	}

	public function testValidLoginCredentials()
	{
		$this->getRequest()
		->setMethod('POST')
		->setPost(array('username' => 'test', 'password' => 'test'));
		$this->dispatch('/login');
		$this->assertEquals($this->getResponse()->getHttpResponseCode(), 200);
		$body = Zend_Json::decode($this->getResponse()->getBody());
		$this->assertNotNull($body);
		$this->assertEquals('/comment/my', $body['redirect_to']);
	}
}
