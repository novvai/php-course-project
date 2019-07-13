<?php

use Novvai\Router\Router;

Router::middlewareGroup("auth", function () {
    Router::get("/", "App\\Http\\Controllers\\Home@index");
    Router::get("/user/{user_id}", "App\\Http\\Controllers\\Home@show");
    Router::post('create-user', "App\\Http\\Controllers\\Home@create");
});
