<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function () {
    
    $user=User::get();
    return response()->json([
            'status' => 1,
            'message' => 'user',
            'user' => $user,

        ],
            200,
            array('Content-Type' => 'application/json;charset:utf-8;'),
            JSON_UNESCAPED_UNICODE
        );
});

 
