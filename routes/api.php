<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("user-registration", [AppController::class, 'registration']);
Route::post("login", [AppController::class, 'login']);

Route::post("registration", [RegistrationController::class, 'save']);

Route::group(['middleware' => ['AuthJwt']], function(){
    Route::get('logout', [AppController::class, 'logout']);

    
    Route::get("check-auth", function(){ return true;});
});