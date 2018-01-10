<?php
namespace CustomerView;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'customer' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/customer-front',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'priority-number' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/priority-number[/:type]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'priority-number',
                    ],
                ],
            ],
            'select-transaction' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/transaction[/:type]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'transaction',
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
            'customer-view' => __DIR__ . '/../view',
        ],
    ],
];