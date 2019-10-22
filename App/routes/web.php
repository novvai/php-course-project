<?php

use Novvai\Router\Router;
Router::middlewareGroup("session", function () {
    Router::get("/", "App\\Http\\Controllers\\Home@index");
    Router::get("/user/{user_id}", "App\\Http\\Controllers\\Home@show");
});
Router::get('/api/shops', "App\\Http\\Controllers\\Shop@index");

Router::post("/api/authenticate", "App\\Http\\Controllers\\Authentication\\Login@process");
Router::post("/api/register", "App\\Http\\Controllers\\Authentication\\Register@process");

Router::middlewareGroup("auth", function () {
    Router::post('/api/shop', "App\\Http\\Controllers\\Shop@create");
    Router::post('/api/shop/{shop_id}', "App\\Http\\Controllers\\Shop@edit");
    Router::delete('/api/shop/{shop_id}', "App\\Http\\Controllers\\Shop@delete");
});
