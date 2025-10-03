<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\ApiController as V1Controller;

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

/**
 * Unversioned responses. In place for transitioning period EPOS.
 */
Route::get('/rock_physics', [V1Controller::class, 'rockPhysics']);
Route::get('/analogue', [V1Controller::class, 'analogue']);
Route::get('/paleo', [V1Controller::class, 'paleo']);
Route::get('/microscopy', [V1Controller::class, 'microscopy']);
Route::get('/geochemistry', [V1Controller::class, 'geochemistry']);
Route::get('/geoenergy', [V1Controller::class, 'geoenergy']);
Route::get('/all', [V1Controller::class, 'all']);
Route::get('/tna', [V1Controller::class, 'tna']);
Route::get('/vocabularies/term', [V1Controller::class, 'term']);
Route::get('/facilities', [V1Controller::class, 'facilities']);

Route::prefix('v1')->group(function () {
    Route::get('/rock_physics', [V1Controller::class, 'rockPhysics']);
    Route::get('/analogue', [V1Controller::class, 'analogue']);
    Route::get('/paleo', [V1Controller::class, 'paleo']);
    Route::get('/microscopy', [V1Controller::class, 'microscopy']);
    Route::get('/geochemistry', [V1Controller::class, 'geochemistry']);
    Route::get('/geoenergy', [V1Controller::class, 'geoenergy']);
    Route::get('/all', [V1Controller::class, 'all']);
    Route::get('/tna', [V1Controller::class, 'tna']);
    Route::get('/vocabularies/term', [V1Controller::class, 'term']);
    Route::get('/facilities', [V1Controller::class, 'facilities']);
});

Route::prefix('v2')->group(function () {

});
