<?php
class Application_Model_UserMapper extends Exampleapp_Mapper
{
	public function __construct()
	{
		parent::__construct('User');
	}

	public function findByUsername($username)
	{
		$r = $this->createStatementFindByColumn('username', $username)->fetch();

		return $this->toObject($r);
	}
}
