<?php
class Application_Model_CommentMapper extends Exampleapp_Mapper
{
	public function findByUser($id)
	{
		$stat = $this->_dbTable->query('SELECT * FROM ' . $this->_dbTable->getViewName() . ' WHERE id_user = ? OR username = ?', array((int)$id, $id));

		$r = $stat->fetchAll();

		return $this->toObjects($r);
	}
}
