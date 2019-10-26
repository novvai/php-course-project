<?php

use Novvai\Router\Router;

Router::middlewareGroup("session", function () {
    Router::get("/", "App\\Http\\Controllers\\Home@index");
    Router::get("/logout", "App\\Http\\Controllers\\Web\\Authentication\\Login@logout");

    Router::middlewareGroup("guest", function () {
        Router::get("/login", "App\\Http\\Controllers\\Web\\Authentication\\Login@index");
        Router::post("/login", "App\\Http\\Controllers\\Web\\Authentication\\Login@process");
    });

    Router::middlewareGroup("web-auth", function () {
        Router::get("/dashboard",  "App\\Http\\Controllers\\Web\\Dashboard@index");
        /** SHOPS */
        Router::get("/shops",  "App\\Http\\Controllers\\Web\\Shops@index");
        Router::get("/shops/create",  "App\\Http\\Controllers\\Web\\Shops@create");
        Router::post("/shops/create",  "App\\Http\\Controllers\\Web\\Shops@create_process");
        Router::get("/shops/{shop_id}/edit",  "App\\Http\\Controllers\\Web\\Shops@edit");
        Router::post("/shops/{shop_id}/edit",  "App\\Http\\Controllers\\Web\\Shops@edit_process");
        Router::delete("/shops/{shop_id}/delete",  "App\\Http\\Controllers\\Web\\Shops@delete");
        /** SHOPS */
    });
});

Router::get('/api/shops', "App\\Http\\Controllers\\Shop@index");

Router::post("/api/authenticate", "App\\Http\\Controllers\\Api\\Authentication\\Login@process");
Router::post("/api/register", "App\\Http\\Controllers\\Api\\Authentication\\Register@process");

Router::middlewareGroup("auth", function () {
    Router::post('/api/shop', "App\\Http\\Controllers\\Shop@create");
    Router::post('/api/shop/{shop_id}', "App\\Http\\Controllers\\Shop@edit");
    Router::delete('/api/shop/{shop_id}', "App\\Http\\Controllers\\Shop@delete");
});
