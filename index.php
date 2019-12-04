<?php

use Novvai\Container;

require_once '../bootstrap/app.php';

$command = 'handle';
$files = glob(base_path() . 'Migrations/*.php');

foreach ($files as $file) {
    require_once $file;
    $fileClassName = end(get_declared_classes());
    
    Container::make($fileClassName)->$command();

    echo "$fileClassName $args[1]! \n";
}

$command = 'handle';
$files = glob(base_path() . 'Seeds/*.php');

foreach ($files as $file) {
    require_once $file;
    $fileClassName = end(get_declared_classes());
    
    Container::make($fileClassName)->$command();

    echo "$fileClassName $args[1]! \n";
}

