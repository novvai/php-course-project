<?php

namespace App\Http\Controllers;

use Novvai\Request\Request;

abstract class Base
{
    protected $request;
    public function __construct()
    {
        $this->request = Request::getInstance();
    }
}
