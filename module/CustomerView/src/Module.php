<?php
namespace CustomerView;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
#use Zend\Db\Adapter\AdapterInterface;
#use Zend\Db\TableGateway\TableGateway;	
#use Zend\Db\ResultSet\ResultSet;	

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /***
    public function getServiceConfig()
    {
    	return [
    		'factories' => [
    			Model\PriorityNumberTable::class => function($container) {
    				$gateway = $container->get(Model\PriorityNumberTableGateway::class);
    				return Model\PriorityNumberTable($gateway);
    			},
    			Model\PriorityNumberTableGateway::class => function($container) {
    				$adapter = $container->get(AdapterConfigInterface::class);
    				$resultSet = new ResultSet();
    				$resultSet->setArrayObjectPrototype(new Model\PriorityNumber);
    				return new TableGateway('priority_number', $adapter, null, $resultSet);
    			},
    		]
    	];
    }

    public function getControllerConfig()
    {
    	return [
    		'factories' => [
    			Controller\IndexController::class => function($container) {
    				return new Controller\IndexController(
    					$container->get(Model\PriorityNumberTable::class);
    				);
    			}
    		]
    	];
    }
    ***/
}