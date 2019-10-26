<?php

use Novvai\Container;
use Novvai\DBDrivers\PdoDriver;
use Novvai\QueryBuilders\PdoBuilder;
use Novvai\DBDrivers\Base as DBDriver;
use Novvai\Middlewares\MiddlewareManager;
use Novvai\DBDrivers\Interfaces\DBConnectionInterface;
use Novvai\QueryBuilders\Interfaces\QueryBuilderInterface;

spl_autoload_register(function ($class_name) {
    $class_name = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);

    include_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "$class_name.php";
});

require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "helpers.php";

DBDriver::setConfigPath(base_path() . '/config/db.php');

MiddlewareManager::register([
    "auth" => [
        App\Middlewares\AuthToken::class
    ],
    "session"=>[
        App\Middlewares\Session::class
    ],
    "guest"=>[
        App\Middlewares\Guest::class
    ]
]);

Container::bind([
    DBConnectionInterface::class => PdoDriver::class,
    QueryBuilderInterface::class => PdoBuilder::class
]);
