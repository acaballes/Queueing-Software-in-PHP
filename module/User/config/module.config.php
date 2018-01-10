<?php
namespace User;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'main-page' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/main[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\MainPageController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'guest-page' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/guest[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\GuestController::class,
                        'action'     => 'guest',
                    ],
                ],
            ]
        ],
    ],
    /*'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    */
    'view_manager' => [
        'template_path_stack' => [
            'user' => __DIR__ . '/../view',
        ],
    ],
];