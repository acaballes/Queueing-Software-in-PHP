<?php
namespace User;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Interop\Container\ContainerInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getControllerConfig()
    {
    	return [
    		'factories' => [
    			Controller\IndexController::class => function(ContainerInterface $container) {
    				return new Controller\IndexController($container);
    			},
                Controller\MainPageController::class => function(ContainerInterface $container) {
                    return new Controller\MainPageController($container);
                },
                Controller\GuestController::class => function(ContainerInterface $container) {
                    return new Controller\GuestController($container);
                }
    		]
    	];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
		       	'User\Model\AuthStorage' => function($sm){
		            return new \User\Model\AuthStorage('user_session');  
		        },
                'AuthService' => function($sm) {
           			$dbAdapter = $sm->get(\Zend\Db\Adapter\Adapter::class);
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 'users','username','password', 'MD5(?)');
		            $authService = new AuthenticationService();
		            $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('User\Model\AuthStorage'));
              
		            return $authService;
		        },
            ]
        ];
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $auth = $sm->get('AuthService');
        if (!$auth->hasIdentity()) {
            $eventManager = $application->getEventManager();
            $eventManager->attach(MvcEvent::EVENT_DISPATCH, function($e) {
                $routeMatch = $e->getRouteMatch();
                $action = $routeMatch->getParam("action");
                //var_dump($action); exit;
                $controller = $routeMatch->getParam("controller");
                if (($action != "update" && $controller != "QueueView\Controller\IndexController") && ($action != "priority-number" && $controller != "CustomerView\Controller\IndexController") && ($action != "index" && $controller != "QueueView\Controller\IndexController") && ($action != "index" && $controller != "CustomerView\Controller\IndexController")) {
                    $routeMatch->setParam('controller', 'User\Controller\IndexController');
                    $routeMatch->setParam('action', 'login');
                }
            }, 1000);
        }
    }
}