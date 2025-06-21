<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;

class UserController
{

    public static function index(Request $request, Response $response)
    {
        return $response->html('hello, world');
    }
}
