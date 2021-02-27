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

Route::post("save_registration", [RegistrationController::class, 'save']);
Route::get("get_divisions", [RegistrationController::class, 'getDivisions']);
Route::post("get_districts", [RegistrationController::class, 'getDistricts']);
Route::post("get_upazilas", [RegistrationController::class, 'getUpazilas']);


Route::group(['middleware' => ['AuthJwt']], function(){
    Route::get('logout', [AppController::class, 'logout']);
    Route::get("check-auth", function(){ return true;});
    
    // get all registrations
    
    /*
    *   get all registrations 
    *   & also filter with customer request
    */
    Route::post("get_applications", [RegistrationController::class, 'getApplications']);
    Route::post("get_application", [RegistrationController::class, 'getApplication']);
    
    Route::post("delete_education", [RegistrationController::class, 'deleteEducation']);
    Route::post("delete_training", [RegistrationController::class, 'deleteTraining']);

    //  update registration form
    Route::post("update_registration", [RegistrationController::class, 'update']);
});