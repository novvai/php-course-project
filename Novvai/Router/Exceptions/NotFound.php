<?php

namespace Novvai\Router\Exceptions;

use Exception;

class NotFound extends Exception
{
    public function __construct($message = null, $code = 0)
    {
        $message = "Executioner not found : $message";

        parent::__construct($message, $code);
    }
}
