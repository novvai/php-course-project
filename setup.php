<?php

use Novvai\Container;

require_once './bootstrap/app.php';

foreach (glob(base_path() . 'Migrations/*.php') as $migration) {
    require_once $migration;
    $migrationClassName = end(get_declared_classes());
    Container::make($migrationClassName)->handle();
}
