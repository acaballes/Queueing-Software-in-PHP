<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MainPageController extends AbstractActionController
{
	protected $serviceManager;

    public function __construct($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
    
	public function indexAction()
	{
		$this->loadLayoutData();
		$user = new \User\Model\User();
		$users = $user->getAllUsers();
		$teller = new \User\Model\Teller();
		$tellers = $teller->getAllTellers();
		$announcement = new \User\Model\Announcement();
		$announcements = $announcement->getAllAnnouncements();
		return new ViewModel(['users' => $users, 'tellers' => $tellers, 'announcements' => $announcements]);
	}

	public function addUserAction()
	{
		$this->loadLayoutData();
		$error = '';
		$request = $this->getRequest();
		if ($request->isPost()) {
			$user = new \User\Model\User();
			try {
				$user->addUser($request->getPost());
				return $this->redirect()->toRoute('main-page', ['action' => 'view-user']);
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
		}
		$teller = new \User\Model\Teller();
		$tellers = $teller->getAllTellers();
		return new ViewModel(['error' => $error, 'tellers' => $tellers]);
	}

	public function viewUserAction()
	{
		$this->loadLayoutData();
		$user = new \User\Model\User();
		$users = $user->getAllUsers();
		return new ViewModel(['users' => $users]);
	}

	public function editUserAction()
	{
		$this->loadLayoutData();
		$user = new \User\Model\User();
		$teller = new \User\Model\Teller();
		$userData = $user->getUserById($this->params()->fromRoute('id'));
		$error = '';
		$request = $this->getRequest();
		if ($request->isPost()) {
			$user = new \User\Model\User();
			try {
				$res = $user->updateUser($this->params()->fromRoute('id'), $request->getPost());
				return $this->redirect()->toRoute('main-page', ['action' => 'manage-user']);
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
		}
		$tellers = $teller->getAllTellers();
		return new ViewModel(['id' => $this->params()->fromRoute('id'), 'user' => $userData, 'tellers' => $tellers, 'error' => $error]);
	}

	public function deleteUserAction()
	{
		$user = new \User\Model\User();
		$users = $user->deleteUser($this->params()->fromRoute('id'));
		return $this->redirect()->toRoute("main-page", ["action" => "manage-user"]);
	}

	public function manageUserAction()
	{
		$this->loadLayoutData();
		$user = new \User\Model\User();
		$users = $user->getAllUsers();
		return new ViewModel(['users' => $users]);
	}

	public function addTellerAction()
	{
		$this->loadLayoutData();
		$error = '';
		$request = $this->getRequest();
		if ($request->isPost()) {
			$teller = new \User\Model\Teller();
			try {
				$teller->addTeller($request->getPost());
				return $this->redirect()->toRoute('main-page', ['action' => 'view-teller']);
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
		}
		$transaction = new \User\Model\Transaction();
		$transactions = $transaction->getAllTransaction();
		return new ViewModel(['error' => $error, 'transactions' => $transactions]);
	}

	public function viewTellerAction()
	{
		$this->loadLayoutData();
		$teller = new \User\Model\Teller();
		$tellers = $teller->getAllTellers();
		return new ViewModel(['tellers' => $this->parseTellersData($tellers)]);
	}

	private function parseTellersData($data)
	{
		$result = [];
		if (!empty($data)) {
			foreach ($data as $k=>&$item) {
				$transaction = new \User\Model\Transaction();
				$transaction = $transaction->getTransactionsByTellerId($item['transactions']);
				$item['transactions_accepted'] = !empty($transaction) ? $this->parseTransactions($transaction) : '';
				$result[] = $item;
			}
		}
		return $result;
	}

	private function parseTransactions($data)
	{
		$res = [];
		for ($i = 0; $i < count($data); $i++) {
			$res[] = $data[$i]['name'];
		}
		return implode(', ', $res);
	}

	public function editTellerAction()
	{
		$this->loadLayoutData();
		$teller = new \User\Model\Teller();
		$tellerData = $teller->getTellerById($this->params()->fromRoute('id'));
		$request = $this->getRequest();
		if ($request->isPost()) {
			$teller = new \User\Model\Teller();
			try {
				$teller->updateTeller($this->params()->fromRoute('id'), $request->getPost());
				return $this->redirect()->toRoute('main-page', ['action' => 'manage-teller']);
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
		}
		$transaction = new \User\Model\Transaction();
		$transactions = $transaction->getAllTransaction();
		return new ViewModel(['id' => $this->params()->fromRoute('id'), 'transactions' => $transactions, 'teller' => $tellerData]);
	}

	public function manageTellerAction()
	{
		$this->loadLayoutData();
		$teller = new \User\Model\Teller();
		$tellers = $teller->getAllTellers();
		return new ViewModel(['tellers' => $this->parseTellersData($tellers)]);
	}

	public function deleteTellerAction()
	{
		$teller = new \User\Model\Teller();
		$teller = $teller->deleteTeller($this->params()->fromRoute('id'));
		return $this->redirect()->toRoute("main-page", ["action" => "manage-teller"]);
	}

	public function addAnnouncementAction()
	{
		$this->loadLayoutData();
		$error = '';
		$request = $this->getRequest();
		if ($request->isPost()) {
			$announcement = new \User\Model\Announcement();
			try {
				$announcement->addAnnouncement($request->getPost());
				return $this->redirect()->toRoute('main-page', ['action' => 'view-announcement']);
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
		}
		return new ViewModel(['error' => $error]);
	}

	public function viewAnnouncementAction()
	{
		$this->loadLayoutData();
		$announcement = new \User\Model\Announcement();
		$announcement = $announcement->getAllAnnouncements();
		return new ViewModel(['announcements' => $announcement]);
	}

	public function editAnnouncementAction()
	{
		$this->loadLayoutData();
		$error = '';
		$announcement = new \User\Model\Announcement();
		$announcement = $announcement->getAnnouncementById($this->params()->fromRoute('id'));
		$request = $this->getRequest();
		if ($request->isPost()) {
			$announcement = new \User\Model\Announcement();
			try {
				$announcement->updateAnnouncement($this->params()->fromRoute('id'), $request->getPost());
				return $this->redirect()->toRoute('main-page', ['action' => 'manage-announcement']);
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
		}
		return new ViewModel(['id' => $this->params()->fromRoute('id'), 'error' => $error, 'announcement' => $announcement]);
	}

	public function deleteAnnouncementAction()
	{
		$teller = new \User\Model\Announcement();
		$teller = $teller->deleteAnnouncement($this->params()->fromRoute('id'));
		return $this->redirect()->toRoute("main-page", ["action" => "manage-announcement"]);
	}

	public function manageAnnouncementAction()
	{
		$this->loadLayoutData();
		$announcement = new \User\Model\Announcement();
		$announcement = $announcement->getAllAnnouncements();
		return new ViewModel(['announcements' => $announcement]);
	}

	public function addTransactionAction()
	{
		$this->loadLayoutData();
		$error = '';
		$request = $this->getRequest();
		if ($request->isPost()) {
			$transaction = new \User\Model\Transaction();
			try {
				$transaction->addTransaction($request->getPost());
				return $this->redirect()->toRoute('main-page', ['action' => 'view-transaction']);
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
		}
		return new ViewModel(['error' => $error]);
	}

	public function editTransactionAction()
	{
		$this->loadLayoutData();
		$error = '';
		$transaction = new \User\Model\Transaction();
		$transaction = $transaction->getTransactionById($this->params()->fromRoute('id'));
		$request = $this->getRequest();
		if ($request->isPost()) {
			$transaction = new \User\Model\Transaction();
			try {
				$transaction->updateTransaction($this->params()->fromRoute('id'), $request->getPost());
				return $this->redirect()->toRoute('main-page', ['action' => 'manage-transaction']);
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
		}
		return new ViewModel(['id' => $this->params()->fromRoute('id'), 'error' => $error, 'transaction' => $transaction]);
	}

	public function viewTransactionAction()
	{
		$this->loadLayoutData();
		$transaction = new \User\Model\Transaction();
		$transactions = $transaction->getAllTransaction();
		return new ViewModel(['transactions' => $transactions]);
	}

	public function manageTransactionAction()
	{
		$this->loadLayoutData();
		$transaction = new \User\Model\Transaction();
		$transactions = $transaction->getAllTransaction();
		return new ViewModel(['transactions' => $transactions]);
	}

	public function deleteTransactionAction()
	{
		$transaction = new \User\Model\Transaction();
		$transaction = $transaction->deleteTransaction($this->params()->fromRoute('id'));
		return $this->redirect()->toRoute("main-page", ["action" => "manage-transaction"]);
	}

	public function changePasswordAction()
	{
		$this->loadLayoutData();
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

	public function resetAction()
	{
		$this->loadLayoutData();
		return new ViewModel();
	}

	public function resetSuccessAction()
	{
		$this->loadLayoutData();
		$pn = new \CustomerView\Model\PriorityNumber();
		$pn->truncatePriority();
		return new ViewModel();
	}

	private function loadLayoutData()
	{
		$auth = $this->serviceManager->get('AuthService');
        $storageData = $auth->getStorage()->read();
		$this->layout()->user = $storageData;
	}
}