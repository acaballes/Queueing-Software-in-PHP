<?php namespace User\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class Announcement {
	// database options
	protected $adapter = [
	    'driver' => 'Mysqli',
	    'database' => 'qms',
	    'username' => 'root',
	    'password' => 'algieadmin'
 	];

	public function addAnnouncement($data)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
 		$insert = $sql->Insert();
		$insert->into('announcements');
		$insert->values(['title' => $data['title'],
			'description' => $data['description']]);
		$insertstmt = $sql->prepareStatementForSqlObject($insert);
		$insertstmt->execute();
		return true;
 	}

 	public function getAllAnnouncements()
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->Select();
		$select->from('announcements');
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$data = $this->getResultArray($results);
		return $data;
 	}

 	public function getAnnouncementById($id)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$select = $sql->Select();
		$select->from('announcements');
		$select->where('id = '.$id);
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		$data = $this->getResultArray($results);
		return !empty($data) ? $data[0] : [];
 	}

 	public function updateAnnouncement($id, $data)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$update = $sql->Update('announcements');
		$update->set(['title' => $data['title'], 'description' => $data['description']]);
		$update->where('id = '. $id);
		try {
			$statement = $sql->prepareStatementForSqlObject($update);
			$results = $statement->execute();
			return true;
		} catch (Exception $e) {
			return $e->getMessage();
		}
 	}

 	public function deleteAnnouncement($id)
 	{
 		$sql = new Sql(new Adapter($this->adapter));
		$delete = $sql->Delete('announcements');
		$delete->where('id = '. $id);
		try {
			$statement = $sql->prepareStatementForSqlObject($delete);
			$results = $statement->execute();
			return true;
		} catch (Exception $e) {
			return false;
		}
 	}

 	private function getResultArray($results)
 	{
 		$resultset = new ResultSet();
 		$data = $resultset->initialize($results)->toArray();
 		return $data;
 	}
}