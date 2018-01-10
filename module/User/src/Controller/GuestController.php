<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class GuestController extends AbstractActionController
{
    protected $serviceManager;

    public function __construct($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function guestAction()
    {
    	$this->loadLayout();
    	$priorities = new \CustomerView\Model\PriorityNumber();
		$result = $priorities->getAllPriorityNumbersAvailableByAssignedTeller(12, $this->storageInfoData());
		$transaction = new \User\Model\Transaction();
		$teller = $priorities->getTellerData($this->storageInfoData());
		$transaction = !empty($teller) ? $transaction->getTransactionsByTellerId($teller[0]['transactions']) : [];
		//print "<pre>"; print_r($this->parseTellersData($transaction)); exit;
        return new ViewModel(['data' => $result, 'teller' => !empty($teller) ? $teller[0] : [], 'transactions' => $this->parseTransactions($transaction)]);
    }

    private function parseTransactions($data)
	{
		$res = [];
		for ($i = 0; $i < count($data); $i++) {
			$res[] = $data[$i]['name'];
		}
		return implode(', ', $res);
	}

    public function changePasswordAction()
	{
		$this->loadLayout();
		$error = '';
		$success = false;
		$request = $this->getRequest();
		if ($request->isPost()) {
			$user = new \User\Model\User();
			$postData = $request->getPost();
			$auth = $this->serviceManager->get('AuthService');
            $storageData = $auth->getStorage()->read();
            if ($postData['new_password'] === $postData['confirm_password']) {
				try {
					$user->changePassword($storageData->id, $postData);
					$success = true;
				} catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
			else {
				$error = "New password and confirm password didn't match!";
			}
		}
		return new ViewModel(['error' => $error, 'success' => $success]);
	}

	public function acceptedAction()
	{
		$this->loadLayout();
		$id = $this->params()->fromRoute('id');
		// check first this priority  number was already taken
		$piorityNumber = new \CustomerView\Model\PriorityNumber();
		$result = $piorityNumber->assignTeller($id, $this->storageInfoData());
		return new ViewModel(['result' => $result]);
	}

	public function doneAction()
	{
		$id = $this->params()->fromRoute('id');
		$priorities = new \CustomerView\Model\PriorityNumber();
		$result = $priorities->doneTransaction($id);
		if ($result) {
			$this->redirect()->toRoute('guest-page');
		}
		else {
			echo "<h2>There is an error updating the transaction. Try again later.</h2>";
			exit;
		}
	}

	public function prioritiesAction()
	{
		$lists = '';
		$priorities = new \CustomerView\Model\PriorityNumber();
		$result = $priorities->getAllPriorityNumbersAvailableByAssignedTeller(12, $this->storageInfoData());
		if (!empty($result)) {
			foreach($result as $pn) {
				$priorityNumber = $pn['pnumber'];
				$lists .= "<div class='panel panel-default'>";
			  	$lists .= "<div class='panel-body'>";
			  	if ($pn['is_sc'] == 1)
			  		$lists .= "<strong>Priority Number P{$priorityNumber}</strong> - {$pn['transaction_str']}";
		  		else
		  			$lists .= "<strong>Priority Number {$priorityNumber}</strong> - {$pn['transaction_str']}";
		  		$lists .= "<a href='/guest/accepted/".$pn['id']."' class='btn btn-primary pull-right'>Accept</a>";
			  	$lists .= "</div>";
			 	$lists .= "</div>";
			}
		}
		else {
			$lists .= "<div class='panel panel-default'>";
			$lists .= "<div class='panel-body'>";
			$lists .= "<h2>No priority numbers in-line assigned to this teller.</h2>";
			$lists .= "</div>";
			$lists .= "</div>";
		}
		echo $lists;
		exit();
	}

	private function loadLayout()
	{
		$auth = $this->serviceManager->get('AuthService');
        $storageData = $auth->getStorage()->read();
		$this->layout('layout/guest.phtml');
		$this->layout()->user = $storageData;
	}

	private function storageInfoData()
	{
		$auth = $this->serviceManager->get('AuthService');
        $storageData = $auth->getStorage()->read();
        return $storageData;
	}
}