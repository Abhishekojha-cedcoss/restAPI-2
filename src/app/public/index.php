<?php

$_SERVER["REQUEST_URI"] = str_replace("/app/", "/", $_SERVER["REQUEST_URI"]);

require "./vendor/autoload.php";

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Config;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Logger\AdapterFactory;
use Phalcon\Logger\LoggerFactory;

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/application');
define('URLROOT', 'http://localhost:8080/');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
    ]
);

$loader->register();

$loader->registerNamespaces(
    [
        "App\Application\Components" => APP_PATH . "/components"
    ]
);

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);
$application = new Application($container);




//.......................................<Logger>........................................//
$container->set(
    'logger',
    function () {
        $adapters = [
            "main"  => new \Phalcon\Logger\Adapter\Stream(APP_PATH . "/storage/log/main.log")
        ];
        $adapterFactory = new AdapterFactory();
        $loggerFactory  = new LoggerFactory($adapterFactory);

        return $loggerFactory->newInstance('prod-logger', $adapters);
    },
    true
);
//.......................................<Logger>........................................//


$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );

        $session
            ->setAdapter($files)
            ->start();

        return $session;
    },
    true
);

$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client(
            "mongodb://mongo",
            array(
                "username" => 'root',
                "password" => "password123"
            )
        );
        return $mongo->store;
    },
    true
);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}