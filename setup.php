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
        break;
    case 'rollback':
        $command = 'rollback';
        break;
    default:
        return "";
}

foreach (glob(base_path() . 'Migrations/*.php') as $migration) {
    require_once $migration;
    $migrationClassName = end(get_declared_classes());
    
    Container::make($migrationClassName)->$command();
}
