<?php

declare(strict_types=1);

$_SERVER['REQUEST_URI'] = str_replace('/frontend/', '/', $_SERVER['REQUEST_URI']);

require './vendor/autoload.php';

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Logger\AdapterFactory;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\LoggerFactory;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View;
use Phalcon\Url;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/application');
define('URLROOT', 'http://localhost:8080/');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
);

$loader->register();

$profiler = new \Fabfuel\Prophiler\Profiler();

$profiler->addAggregator(new
    \Fabfuel\Prophiler\Aggregator\Database\QueryAggregator());
$profiler->addAggregator(new
    \Fabfuel\Prophiler\Aggregator\Cache\CacheAggregator());

$toolbar = new \Fabfuel\Prophiler\Toolbar($profiler);
$toolbar->addDataCollector(new
    \Fabfuel\Prophiler\DataCollector\Request());

echo $toolbar->render();

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
            'main' => new Stream(APP_PATH . '/storage/log/main.log')
        ];
        $adapterFactory = new AdapterFactory();
        $loggerFactory  = new LoggerFactory($adapterFactory);

        return $loggerFactory->newInstance('prod-logger', $adapters);
    },
    true
);
//.......................................<Logger>........................................//

$container->set(
    'mongo',
    function () {
        $mongo = new \MongoDB\Client(
            'mongodb://mongo',
            [
                'username' => 'root',
                'password' => 'password123',
            ]
        );
        return $mongo->frontend;
    },
    true
);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER['REQUEST_URI']
    );

    $response->send();
} catch (\Exception $err) {
    echo 'Exception: ', $err->getMessage();
}
