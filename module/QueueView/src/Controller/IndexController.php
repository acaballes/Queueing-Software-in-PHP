<?php

namespace QueueView\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CustomerView\Model\PriorityNumber;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$this->layout('layout/queue-view.phtml');
    	$data = new PriorityNumber();
    	$priorityNumbers = $data->getAllPriorityNumbersAvailable();
        $tellers = new \User\Model\Teller();
        $tellerData = $tellers->getAllTellers();
        $anouncement = new \User\Model\Announcement();
        $anouncementData = $anouncement->getAllAnnouncements();
    	$inline = empty($priorityNumbers) ? 'No Pending Priority Numbers' : $this->parsePriorityNumbersInLine($priorityNumbers);
        return new ViewModel(['data' => $priorityNumbers, 'inline' => $inline, 'tellers' => $tellerData, 'anouncements' => $this->parseAnnouncement($anouncementData)]);
    }

    public function updateAction()
    {
    	$data = new PriorityNumber();
        $tellers = new \User\Model\Teller();
    	$priorityNumbers = $data->getAllPriorityNumbersAvailable();
        $tellerData = $tellers->getCurrentTransactions();
        if (empty($tellerData)) {
            $serving = 'No Priority Number to serve.';
        }
        //print "<pre>"; print_r($tellerData); exit;
    	$inline = empty($priorityNumbers) ? 'No Pending Priority Numbers' : $this->parsePriorityNumbersInLine($priorityNumbers);
        echo json_encode(['tellers' => $tellerData, 'in_line' => $inline]);
        exit();
    }

    private function parsePriorityNumbersInLine($data)
    {
    	$res = [];
    	foreach ($data as $k=>$pn) {
    		$res[] = $pn['is_sc'] == 1 ? 'P'.$pn['pnumber'] : $pn['pnumber'];
    	}
    	return implode(" <span class='glyphicon glyphicon-arrow-right'></span> ", $res);
    }

    private function parseAnnouncement($data)
    {
        $items = [];
        if (!empty($data)) {
            foreach ($data as $item) {
                $items[] = '<strong>' . $item['title'] . '</strong>: ' . $item['description']; 
            }
        }
        return !empty($items) ? implode(' | ' ,$items) : '';
    }
}
