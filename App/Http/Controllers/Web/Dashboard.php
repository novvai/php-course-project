<?php

namespace App\Http\Controllers\Web;

use Novvai\Response\Response;
use App\Http\Controllers\Base;

class Dashboard extends Base
{
    public function index()
    {
        Response::withTemplate('dashboard/index');
    }
}
