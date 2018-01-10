<?php
namespace QueueView;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'queue' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/queue-front',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'ajax_update_data' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/update',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'update',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'queue-view' => __DIR__ . '/../view',
        ],
    ],
];