<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

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

Route::get('/rock_physics', [ApiController::class, 'rockPhysics']);
Route::get('/analogue', [ApiController::class, 'analogue']);
Route::get('/paleo', [ApiController::class, 'paleo']);
Route::get('/microscopy', [ApiController::class, 'microscopy']);
Route::get('/geochemistry', [ApiController::class, 'geochemistry']);
Route::get('/geoenergy', [ApiController::class, 'geoenergy']);
Route::get('/all', [ApiController::class, 'all']);
Route::get('/tna', [ApiController::class, 'tna']);
Route::get('/vocabularies/term', [ApiController::class, 'term']);
Route::get('/facilities', [ApiController::class, 'facilities']);
