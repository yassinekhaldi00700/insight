<?php

namespace App\Http\Controllers\N2;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller2 extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
