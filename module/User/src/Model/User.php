<?php

namespace User\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class User {
	// database options
	protected $adapter = [
	    'driver' => 'Mysqli',
	    'database' => 'qms',
	    'username' => 'root',
	    'password' => 'algieadmin'
 	];

 	public function addUser($data)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
 		$insert = $sql->Insert();
		$insert->into('users');
		$insert->values(['username' => $data['username'],
			'password' => md5($data['password']),
			'firstname' => $data['firstname'],
			'lastname' => $data['lastname'],
			'is_admin' => 0,
			'teller_assigned' => $data['teller']]);
		$insertstmt = $sql->prepareStatementForSqlObject($insert);
		$insertstmt->execute();
		return true;
 	}

 	public function getAllUsers()
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->Select();
		$select->from(['a' => 'users']);
		$select->join(['b' => 'tellers'], 'a.teller_assigned = b.id', ['name', 'teller_id' => 'id'], $select::JOIN_LEFT);
		$select->where(['is_admin' => 0]);
		$select->order('a.id DESC');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$data = $this->getResultArray($results);
		return $data;
 	}

 	public function getUserById($id)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->Select();
		$select->from(['a' => 'users']);
		$select->where(['id' => $id]);
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$data = $this->getResultArray($results);
		return !empty($data) ? $data[0] : [];
 	}

 	public function updateUser($id, $data)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$update = $sql->Update('users');
		$update->set(['firstname' => $data['firstname'],
			'lastname' => $data['lastname'],
			'username' => $data['username'],
			'teller_assigned' => $data['teller']]);
		$update->where('id = '. $id);
		try {
			$statement = $sql->prepareStatementForSqlObject($update);
			$results = $statement->execute();
			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
 	}

 	public function deleteUser($id)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$delete = $sql->Delete('users');
		$delete->where('id = '. $id);
		try {
			$statement = $sql->prepareStatementForSqlObject($delete);
			$results = $statement->execute();
			return true;
		} catch (Exception $e) {
			return false;
		}
 	}

 	public function changePassword($id, $data)
 	{	
 		$sql = new Sql(new Adapter($this->adapter));
		$update = $sql->Update('users');
		$update->set(['password' => md5($data['new_password'])]);
		$update->where('id = '. $id);
		try {
			$statement = $sql->prepareStatementForSqlObject($update);
			$results = $statement->execute();
			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
 	}

 	private function getResultArray($results)
 	{
 		$resultset = new ResultSet();
 		$data = $resultset->initialize($results)->toArray();
 		return $data;
 	}
}