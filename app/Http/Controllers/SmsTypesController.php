<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmsTypesController extends Controller
{
    //
    enum SmsTypes: int
    {
        // todo : add other types
        case LOGIN_VERIFY_CODE = 1;
    }
}
