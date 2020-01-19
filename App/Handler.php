<?php

namespace App;

use Exception;
use Novvai\Response\Response;

class Handler
{
    /**
     * Global handler for Exceptions
     * Active only in production mode
     * Prevents displaying Exception error stack
     * 
     * @param Exception $Ex
     */
    static public function handle(Exception $ex)
    {
        if ($ex instanceof \Novvai\Router\Exceptions\NotFound) {
            return Response::make(404)->withTemplate('errors/404');
        }
        return Response::make(500)->withTemplate('errors/500');
    }
}
