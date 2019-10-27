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
        Router::post("/shops/create",  "App\\Http\\Controllers\\Web\\Shops@processCreate");
        Router::get("/shops/{shop_id}/edit",  "App\\Http\\Controllers\\Web\\Shops@edit");
        Router::post("/shops/{shop_id}/edit",  "App\\Http\\Controllers\\Web\\Shops@processEdit");
        Router::delete("/shops/{shop_id}/delete",  "App\\Http\\Controllers\\Web\\Shops@delete");
        /** SHOPS */

        /** Product Categories */
        Router::get("/product-categories", "App\\Http\\Controllers\\Web\\ProductCategories@index");
        Router::post("/product-categories/create", "App\\Http\\Controllers\\Web\\ProductCategories@processCreate");
        Router::post("/product-categories/{cat_id}/edit", "App\\Http\\Controllers\\Web\\ProductCategories@processEdit");
        Router::delete("/product-categories/{cat_id}/delete", "App\\Http\\Controllers\\Web\\ProductCategories@delete");
        Router::get("/api/product-categories/{cat_id}/sub-categories", "App\\Http\\Controllers\\Web\\ProductCategories@getSubCategories");
        /** Product Categories */
        /** Products */
        Router::get("/products", "App\\Http\\Controllers\\Web\\Product@index");
        Router::get("/products/{product_id}", "App\\Http\\Controllers\\Web\\Product@view");
        Router::get("/products/create", "App\\Http\\Controllers\\Web\\Product@create");
        Router::post("/products/create", "App\\Http\\Controllers\\Web\\Product@processCreate");
        Router::get("/products/{product_id}/edit", "App\\Http\\Controllers\\Web\\Product@edit");
        Router::post("/products/{product_id}/edit", "App\\Http\\Controllers\\Web\\Product@processEdit");
        Router::delete("/products/{product_id}/delete", "App\\Http\\Controllers\\Web\\Product@delete");
        /** Products */
        /** Blog */
        Router::get("/posts", "App\\Http\\Controllers\\Web\\Posts@index");
        Router::get("/posts/{post_id}", "App\\Http\\Controllers\\Web\\Posts@view");
        Router::get("/posts/create", "App\\Http\\Controllers\\Web\\Posts@create");
        Router::post("/posts/create", "App\\Http\\Controllers\\Web\\Posts@processCreate");
        Router::get("/posts/{post_id}/edit", "App\\Http\\Controllers\\Web\\Posts@edit");
        Router::post("/posts/{post_id}/edit", "App\\Http\\Controllers\\Web\\Posts@processEdit");
        Router::delete("/posts/{post_id}/delete", "App\\Http\\Controllers\\Web\\Posts@delete");
        /** Blog */
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
