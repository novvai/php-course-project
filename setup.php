<?php

use Novvai\Container;

require_once './bootstrap/app.php';

if(!isset($argv[1])){
    return ;
}
$args = explode("=", $argv[1]);
if($args[0]!="--action"){
    return;
}
$command = "";


switch($args[1]){
    case 'up':
        $command = 'handle';
        $files = glob(base_path() . 'Migrations/*.php');
        break;
    case 'seed':
        $command = 'handle';
        $files = glob(base_path() . 'Seeds/*.php');
        break;
    case 'rollback':
        // Rolling migrations back should actually execute the handler in reverse order
        $files = glob(base_path() . 'Migrations/*.php');
        $files = array_reverse($files);
        $command = 'rollback';
        break;
    default:
        return "";
}

foreach ($files as $file) {
    require_once $file;
    $fileClassName = end(get_declared_classes());
    
    Container::make($fileClassName)->$command();

    echo "$fileClassName $args[1]! \n";
}
