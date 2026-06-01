<?php

namespace App\Http\Controllers;

enum SmsTypes: int
{
    // todo : add other types
    case LOGIN_VERIFY_CODE = 1;
}
