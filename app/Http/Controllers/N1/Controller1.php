<?php

namespace App\Http\Controllers\N1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller1 extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
