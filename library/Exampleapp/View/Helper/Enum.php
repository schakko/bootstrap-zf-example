<?php
class Exampleapp_View_Helper_Enum extends Zend_View_Helper_Abstract
{
	/**
	 * @return Exampleapp_View_Helper_Enum
	 */
	public function enum()
	{
		return $this;
	}
	
	/**
	 * converts an enum to a self describing name. use this helper inside your view by calling $this->enum()->commentType($commentType)
	 * @param Application_Model_Enum_CommentType::|integer
	 * @return string
	 */
	public function commentType($commentType)
	{
		switch ($commentType) {
			case Application_Model_Enum_CommentType::THUMBS_UP:
				return "+1";
			case Application_Model_Enum_CommentType::THUMBS_DOWN:
				return "-1";
			default:
				return "unknown comment type. This is definitely a fuckin' bug";
		}
	}
}
