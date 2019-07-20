<?php

use Novvai\Router\Router;
Router::get("/", "App\\Http\\Controllers\\Home@index");
Router::get("/user/{user_id}", "App\\Http\\Controllers\\Home@show");

Router::middlewareGroup("auth", function () {
    Router::post('/api/shop', "App\\Http\\Controllers\\Shop@create");
    Router::post('/api/shop/{shop_id}', "App\\Http\\Controllers\\Shop@edit");
    Router::delete('/api/shop/{shop_id}', "App\\Http\\Controllers\\Shop@delete");
});
