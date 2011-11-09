<?php 
class Application_Model_User extends Exampleapp_Model implements Zend_Acl_Resource_Interface
{
	public $password;
	
	public $username;

	public $surname;

	public $firstname;

	public $num_comments;
	
	public function getResourceId()
	{
		return 'user';
	}
}
