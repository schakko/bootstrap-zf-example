<?php
/**
 * Demonstrate own assertions for Zend_Acl
 */
class Exampleapp_Acl_CommentAssertion implements Zend_Acl_Assert_Interface
{
	/** define available privileges */

	/** DELETE means... well.. delete a comment */
	const DELETE = 'delete';
	
	/** CREATE... */
	const CREATE = 'create';

	public function __construct()
	{
	}

	/**
	 * assert given resource type
	 *
	 * @param Zend_Acl $acl
	 * @param Zend_Acl_Role_Interface|Bootstrap_Model_SessionUser $user
	 * @param Zend_Acl_Resource_Interface|Application_Model_Comment
	 * @param $privilege
	 * @return bool
	 */
	public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $user = null, Zend_Acl_Resource_Interface $comment = null, $privilege = null)
	{
		if ($comment == null || (!$comment instanceof Application_Model_Comment)) {
			throw new Exception("Only valid comment objects can be asserted");
		}

		$userIsOwner = ($user->getId() == $comment->id_user);

		// user is owner of comment
		if ($userIsOwner) {
			// owner has privilege to delete a comment
			return in_array($privilege, array(self::DELETE));
		}

		// authenticated user are allowed to create a comment
		if ($user->isLoggedIn()) {
			return in_array($privilege, array(self::CREATE));
		}

		// invalid combination -> deny
		return false;
	}
}
