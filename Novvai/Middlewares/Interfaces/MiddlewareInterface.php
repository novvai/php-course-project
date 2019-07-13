<?php

namespace Novvai\Middlewares\Interfaces;

interface MiddlewareInterface{
    public function handle($request, callable $next);
}