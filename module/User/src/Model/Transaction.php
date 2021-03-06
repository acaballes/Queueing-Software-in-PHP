<?php

namespace User\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class Transaction {
	// database options
	protected $adapter = [
	    'driver' => 'Mysqli',
	    'database' => 'qms',
	    'username' => 'root',
	    'password' => 'algieadmin'
 	];

 	public function addTransaction($data)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
 		$insert = $sql->Insert();
		$insert->into('transactions');
		$insert->values(['name' => $data['name']]);
		$insertstmt = $sql->prepareStatementForSqlObject($insert);
		$insertstmt->execute();
		return true;
 	}

 	public function getAllTransaction()
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->Select();
		$select->from('transactions');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$data = $this->getResultArray($results);
		return $data;
 	}

 	public function getTransactionById($id)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->Select();
		$select->from('transactions');
		$select->where('id = '.$id);
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$data = $this->getResultArray($results);
		return !empty($data) ? $data[0] : [];
 	}

 	public function getTransactionsByTellerId($ids)
 	{
 		if (empty($ids)) return [];
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->Select();
		$select->from('transactions');
		$select->where('id IN ('.$ids.')');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$data = $this->getResultArray($results);
		return !empty($data) ? $data : [];
 	}

 	public function updateTransaction($id, $data)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$update = $sql->Update('transactions');
		$update->set(['name' => $data['name']]);
		$update->where('id = '. $id);
		try {
			$statement = $sql->prepareStatementForSqlObject($update);
			$results = $statement->execute();
			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
 	}

 	public function deleteTransaction($id)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$delete = $sql->Delete('transactions');
		$delete->where('id = '. $id);
		try {
			$statement = $sql->prepareStatementForSqlObject($delete);
			$results = $statement->execute();
			return true;
		} catch (Exception $e) {
			return false;
		}
 	}

 	public function getCurrentTransactions()
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->Select();
		$select->from(['a' => 'tellers']);
		$select->join(['b' => 'priority_number'], 'a.id = b.assigned_teller', ['pnid' => 'id', 'pnumber', 'is_done', 'is_sc']);
		$select->where(['is_serving' => 1, 'is_done' => 0]);
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$data = $this->getResultArray($results);
		return $data;
 	}

 	private function getResultArray($results)
 	{
 		$resultset = new ResultSet();
 		$data = $resultset->initialize($results)->toArray();
 		return $data;
 	}

 	private function getUserAssignedTeller($data)
 	{
 		$result = [];
 		$sql = new Sql(new Adapter($this->adapter));
 		foreach ($data as $k=>$item) {
			$select = $sql->Select();
			$select->from('users');
			$select->columns(['firstname', 'lastname']);
			$select->where('teller_assigned = '. $item['id']);
			$statement = $sql->prepareStatementForSqlObject($select);
			$results = $statement->execute();
			$users = $this->getResultArray($results);
			$result[] = array_merge($item, ['user' => $this->parseUserName($users)]);
		}
		return $result;
 	}

 	private function parseUserName($users)
 	{
 		$result = [];
 		foreach ($users as $user) {
 			$result[] = $user['firstname'] . ' ' . $user['lastname'];
 		}
 		return empty($result) ? '<i>not assigned</i>' : implode(', ', $result);
 	}
}