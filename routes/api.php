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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('incidents', '\App\Http\Controllers\Api\IncidentController@index');
Route::get('incidents/{id}', '\App\Http\Controllers\Api\IncidentController@show');

Route::get('legislators', '\App\Http\Controllers\Api\LegislatorController@index');

Route::post('link-submission', '\App\Http\Controllers\Api\LinkSubmissionController@store');
