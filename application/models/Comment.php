<?php
/**
 * Every Comment belongs to one User.
 */
class Application_Model_Comment extends Exampleapp_Model implements Zend_Acl_Resource_Interface
{
	/**
	 * @var string
	 * @MapToColumn(dbExpression = 'NOW()')
	 */
	public $created_on;
	
	/**
	 * @MapToColumn
	 * @var string
	 */
	public $comment;
	
	/**
	 * @MapToColumn
	 * @var integer
	 */
	public $id_user;
	
	/**
	 * username is only available if model is catched through the view
	 * @var string
	 */
	public $username;
	

	/**
	 * @MapToColumn
	 * @var integer
	 */
	public $comment_type;

	/**
	 * Mark this model as "comment" resource so that Zend_Acl can handle it
	 */ 
	public function getResourceId()
	{
		return 'comment';
	}
}
