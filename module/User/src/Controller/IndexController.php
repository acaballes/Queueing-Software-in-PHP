<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

class IndexController extends AbstractActionController
{
    protected $serviceManager;

    public function __construct($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function loginAction()
    {
        $error = '';
        $this->layout('layout/login.phtml');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $this->getRequest()->getPost();
            $auth = $this->serviceManager->get('AuthService');
            $adapter = $auth->getAdapter();
            $adapter->setIdentity($data['username']);
            $adapter->setCredential($data['password']);                        
            $result = $adapter->authenticate();

            if ($result->isValid()) {
                $rowData = $adapter->getResultRowObject();
                // write session data
                $auth->getStorage()->write($rowData);
                if ($rowData->is_admin) {
                    return $this->redirect()->toRoute('main-page');
                }
                else {
                    return $this->redirect()->toRoute('guest-page');
                }
            }
            else {
                $error = "Supplied login credential is incorrect or the user did not exist.";
            }
        }
        return new ViewModel(['error' => $error]);
    }

    public function logoutAction()
    {
        $auth = $this->serviceManager->get('AuthService');
        $auth->clearIdentity();
        return $this->redirect()->toRoute('login');
    }
}
