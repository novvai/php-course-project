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
$migrations = glob(base_path() . 'Migrations/*.php');

switch($args[1]){
    case 'up':
        $command = 'handle';
        break;
    case 'rollback':
        // Rolling migrations back should actually execute the handler in reverse order
        $migrations = array_reverse($migrations);
        $command = 'rollback';
        break;
    default:
        return "";
}

foreach ($migrations as $migration) {
    require_once $migration;
    $migrationClassName = end(get_declared_classes());
    
    Container::make($migrationClassName)->$command();

    echo "$migrationClassName $args[1]! \n";
}
