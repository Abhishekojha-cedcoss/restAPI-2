<?php

declare(strict_types=1);

require './vendor/autoload.php';

use Api\Handler\Order;
use Api\Handler\Product;
use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Loader;
use Phalcon\Mvc\Micro;

$loader = new Loader();
$loader->registerNamespaces(
    [
        'Api\Components' => './components',
        'Api\Handler' => './handler'
    ]
);

$loader->register();

$container = new FactoryDefault();
$app = new Micro($container);

$container->set(
    'mongo',
    static function () {
        $mongo = new \MongoDB\Client(
            'mongodb://mongo',
            [
                'username' => 'root',
                'password' => 'password123'
            ]
        );
        return $mongo->store;
    },
    true
);

$prod = new Product();
$order = new Order();

$eventsManager = new EventsManager();
$eventsManager->attach(
    'micro',
    new Api\Components\GenerateToken()
);
$app->before(
    new Api\Components\GenerateToken()
);

$app->get(
    '/api/products/list',
    [
        $prod,
        'list'
    ]
);

$app->get(
    '/api/generatetoken',
    [
        $prod,
        'generateToken'
    ]
);

$app->post(
    '/api/orders/placeorder',
    [
        $order,
        'place'
    ]
);

$app->put(
    '/api/orders/update',
    [
        $order,
        'update'
    ]
);

$app->setEventsManager($eventsManager);
$app->handle(
    $_SERVER['REQUEST_URI']
);
