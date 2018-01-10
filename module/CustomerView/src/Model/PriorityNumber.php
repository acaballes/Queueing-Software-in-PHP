<?php

namespace CustomerView\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class PriorityNumber {
	// database options
	protected $adapter = [
	    'driver' => 'Mysqli',
	    'database' => 'qms',
	    'username' => 'root',
	    'password' => 'algieadmin'
 	];

 	public function getAllPriorityNumbers($limit = 50)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->select();
		$select->from('priority_number');
		$select->limit($limit);
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$data = $this->getResultArray($results);
		return $data;
 	}

 	public function getAllPriorityNumbersAvailable($limit = 48)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->select();
		$select->from('priority_number');
		$select->where(['is_serving' => null]);
		$select->order('is_sc ASC, pnumber ASC');
		$select->limit($limit);
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$data = $this->getResultArray($results);
		return $data;
 	}

 	public function getAllPriorityNumbersAvailableByAssignedTeller($limit = 48, $teller)
 	{
 		$tellerData = $this->getTellerData($teller);
		$transactionIds = !empty($tellerData) ? $tellerData[0]['transactions'] : 0;

 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->select();
		$select->from('priority_number');
		$select->where("transaction_type IN ({$transactionIds})");
		$select->having(['is_serving' => null]);
		$select->order('is_sc ASC, pnumber ASC');
		$select->limit($limit);
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$data = $this->getResultArray($results);
		return $data;
 	}

 	public function getTellerData($teller)
 	{
 		// get teller data first to get what are the accepted transactions
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->select();
		$select->from('tellers');
		$select->where(['id' => $teller->teller_assigned]);
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$tellerData = $this->getResultArray($results);
		return $tellerData;
 	}

 	/*
 	 * @param type either 'senior' or 'guest'
 	 */
 	public function generatePriorityNumber($type, $get)
 	{
 		$response = null;
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->select();
		$select->from('priority_number');
		$select->where(['is_sc' => 2]); // check that this pn is not belongs to sc
		$select->limit(1);
		$select->order('id DESC');
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$data = $this->getResultArray($result);
		if (!empty($data)) {
			$priorityNumber = $data[0]['pnumber'] + 1;
			// generate new priority number
			$insert = $sql->Insert();
			$insert->into('priority_number');
			$insert->values(['pnumber' => $priorityNumber, 'is_sc' => 2, 'is_done' => 0, 'transaction_type' => $get['transaction'], 'transaction_str' => $get['transaction_str']]);
			$insertstmt = $sql->prepareStatementForSqlObject($insert);
			try {
				$insertstmt->execute();
				$response = $priorityNumber;
			} catch(\Exception $e) {
				$response = $e->getMessage();
			}
		}
		else {
			// add first priority number
			$insert = $sql->Insert();
			$insert->into('priority_number');
			$insert->values(['pnumber' => 1, 'is_sc' => 2, 'is_done' => 0, 'transaction_type' => $get['transaction'], 'transaction_str' => $get['transaction_str']]);
			$insertstmt = $sql->prepareStatementForSqlObject($insert);
			try {
				$insertstmt->execute();
				$response = 1;
			} catch(\Exception $e) {
				$response = $e->getMessage();
			}
		}
		return $response;
 	}


 	public function generateSeniorCitizenPriorityNumber($type, $get)
 	{
 		$response = null;
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->select();
		$select->from('priority_number');
		$select->where(['is_sc' => 1]); // check that this pn is not belongs to sc
		$select->limit(1);
		$select->order('id DESC');
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$data = $this->getResultArray($result);
		if (!empty($data)) {
			$priorityNumber = $data[0]['pnumber'] + 1;
			// generate new priority number
			$insert = $sql->Insert();
			$insert->into('priority_number');
			$insert->values(['pnumber' => $priorityNumber, 'is_done' => 0, 'is_sc' => 1,'transaction_type' => $get['transaction'], 'transaction_str' => $get['transaction_str']]);
			$insertstmt = $sql->prepareStatementForSqlObject($insert);
			try {
				$insertstmt->execute();
				$response = $priorityNumber;
			} catch(\Exception $e) {
				$response = $e->getMessage();
			}
		}
		else {
			// add first priority number
			$insert = $sql->Insert();
			$insert->into('priority_number');
			$insert->values(['pnumber' => 1, 'is_sc' => 1, 'is_done' => 0, 'transaction_type' => $get['transaction'], 'transaction_str' => $get['transaction_str']]);
			$insertstmt = $sql->prepareStatementForSqlObject($insert);
			try {
				$insertstmt->execute();
				$response = 1;
			} catch(\Exception $e) {
				$response = $e->getMessage();
			}
		}
		return $response;
 	}

 	/*******
 	public function generateSeniorCitizenPriorityNumber($type)
 	{
 		$response = null;
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->select();
		$select->from('priority_number');
		$select->where(['is_serving' => 1]);
		$select->limit(1);
		$select->order('id DESC');
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$data = $this->getResultArray($result);
		if (!empty($data)) {
			$pnumber_next = $data[0]['pnumber'] + 1;
			$res = $this->getSeniorCitizenPN($data[0]['pnumber'], $pnumber_next);
			if (!empty($res)) {
				$senioCitizenPN = $res['pnumber'] + .1;
			}
			else {
				$senioCitizenPN = $data[0]['pnumber'] + .1;
			}
			// insert the new senior citizen priority number to the database
			$insert = $sql->Insert();
			$insert->into('priority_number');
			$insert->values(['pnumber' => $senioCitizenPN, 'is_sc' => 1]);
			$insertstmt = $sql->prepareStatementForSqlObject($insert);
			try {
				$insertstmt->execute();
			} catch(\Exception $e) {
				$response = $e->getMessage();
			}
		}
		else {
			// get the first most priority number
			$sql = new Sql(new Adapter($this->adapter));
			$select = $sql->select();
			$select->from('priority_number');
			$select->limit(1);
			$select->order('id ASC');
			$statement = $sql->prepareStatementForSqlObject($select);
			$result = $statement->execute();
			$data = $this->getResultArray($result);
			if (!empty($data)) {
				//default pn
				$default = $data[0]['pnumber'];
				// get if there are queued sc numbers
				$sql = new Sql(new Adapter($this->adapter));
				$select = $sql->select();
				$select->from('priority_number');
				$select->where->greaterThan('pnumber', $default);
				$select->where->lessThan('pnumber', $default + 1);	
				$select->limit(1);
				$select->order('id DESC');
				$statement = $sql->prepareStatementForSqlObject($select);
				$result = $statement->execute();
				$data = $this->getResultArray($result);
				
				$scpn = !empty($data) ? $data[0]['pnumber'] + .1 : $default + .1;
			}
			else {
				$scpn = 1;
			}

			$senioCitizenPN = $scpn;
			$insert = $sql->Insert();
			$insert->into('priority_number');
			$insert->values(['pnumber' => $scpn, 'is_sc' => $scpn == 1 ? null : 1]);
			$insertstmt = $sql->prepareStatementForSqlObject($insert);
			try {
				$insertstmt->execute();
			} catch(\Exception $e) {
				$response = $e->getMessage();
			}
		}
		return $senioCitizenPN;
 	}

 	*****/

 	private function getSeniorCitizenPN($pn, $pnn)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->select();
		$select->from('priority_number');
		$select->where->greaterThan('pnumber', $pn);
		$select->where->lessThan('pnumber', $pnn);	
		$select->limit(1);
		$select->order('id DESC');
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$data = $this->getResultArray($result);
		return !empty($data) ? $data[0] : [];
 	}

 	public function assignTeller($id, $teller)
 	{
 		// check if the priority number was taken
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->select();
		$select->from('priority_number');
		//$select->columns(['pnumber']);
		$select->where(['id' => $id]);
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$data = $this->getResultArray($result);
		if ($data[0]['assigned_teller'] == $teller->teller_assigned && !$data[0]['is_done']) {
			return [true, $data[0]];
		}
		if (is_null($data[0]['is_serving'])) {
			// assign this teller to this priority number
			$sql = new Sql(new Adapter($this->adapter));
			$update = $sql->Update('priority_number');
			$update->set(['is_serving' => 1, 'assigned_teller' => $teller->teller_assigned]);
			$update->where('id = '. $id);
			try {
				$statement = $sql->prepareStatementForSqlObject($update);
				$results = $statement->execute();
				return [true, $data[0]];
			} catch (Exception $e) {
				return [false, $e->getMessage()];
			}
		}
		return [false, 'not found'];
 	}

 	public function doneTransaction($id)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$update = $sql->Update('priority_number');
		$update->set(['is_done' => 1]);
		$update->where('id = '. $id);
		try {
			$statement = $sql->prepareStatementForSqlObject($update);
			$results = $statement->execute();
			return true;
		} catch (Exception $e) {
			return false;
		}
		return false;
 	}

 	private function getResultArray($results)
 	{
 		$resultset = new ResultSet();
 		$data = $resultset->initialize($results)->toArray();
 		return $data;
 	}

 	//this will reset the priority number table
 	public function truncatePriority()
 	{
 		$adapter = new Adapter($this->adapter);
 		try {
 			$statement = $adapter->createStatement("TRUNCATE TABLE priority_number");
			$result = $statement->execute();
 			return true;
 		} catch(Exception $e)
 		{	
 			return false;
 		}
 	}
}
