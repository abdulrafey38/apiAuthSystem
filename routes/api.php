<?php

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
Route::post('/login','apiController@login');//Api Route for Login
Route::post('/register','apiController@register');//Api Route for Registration
Route::get('/verify','apiController@verify')->name('verify.user');//Password Verify Mail Redirects to this route
Route::post('/password/sendemail','apiController@sendEmail');////Calling for send password reset mail from this link
Route::post('/password/areset', 'apiController@reset'); //after providing new password submit form button redirects to this link

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('get','apiController@loginuser');//get logged in user with sanctum auth token
    Route::post('/logout','apiController@logout');//logout

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('hget','apiController@user');//return all users