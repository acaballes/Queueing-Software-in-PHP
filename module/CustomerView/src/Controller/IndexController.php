<?php

namespace CustomerView\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CustomerView\Model\PriorityNumber;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$this->layout('layout/customer-view.phtml');
        return new ViewModel();
    }

    public function priorityNumberAction()
    {
    	$isAllowed = true;
    	$type = $this->params()->fromRoute('type');
    	if(empty($type) || ($type != 'senior' && $type != 'guest')) {
    		$isAllowed = false;
    	}
    	else {
    		// generate a priority number
    		$data = new PriorityNumber();
            if ($type == 'guest') {
    		    $priorityNumber = $data->generatePriorityNumber($type, $_GET);
            }
            else {
                $priorityNumber = 'P'. $data->generateSeniorCitizenPriorityNumber($type, $_GET);
            }
    	}
    	$this->layout('layout/customer-view.phtml');
        return new ViewModel(['isAllowed' => $isAllowed, 'priorityNumber' => $priorityNumber]);
    }

    public function transactionAction()
    {
        $this->layout('layout/customer-view.phtml');
        $transaction = new \User\Model\Transaction();
        $transactions = $transaction->getAllTransaction();
        return new ViewModel(['type' => $this->params()->fromRoute('type'), 'transactions' => $transactions]);
    }

    // print to a printer socket
    private function printToPrinter()
    {
        try {
            $fp = pfsockopen("192.168.254.102", 9100);
            fputs($fp, "hey");
            fclose($fp);
            return true;
        } catch (Exception $e) {
            print $e->getMessage();
        }
        return false;
    }
}
