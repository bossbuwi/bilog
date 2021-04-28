<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SystemController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//user routes
Route::post('login/', [UserController::class, 'store']);
Route::get('users/', [UserController::class, 'index']);
Route::get('admin/', [UserController::class, 'show']);
Route::put('users/', [UserController::class, 'updateAdmins']);

//rule routes
Route::get('rules/', [RuleController::class, 'index']);
Route::post('rules/', [RuleController::class, 'store']);

//system routes
Route::get('systems/', [SystemController::class, 'index']);
Route::post('systems/', [SystemController::class, 'store']);

//event routes
Route::get('events/', [EventController::class, 'index']);
Route::get('event/', [EventController::class, 'show']);
Route::post('reserve/', [EventController::class, 'store']);
Route::get('report/', [EventController::class, 'generateEventReport']);
Route::put('reserve/', [EventController::class, 'store']);

//config routes
Route::get('config/', [ConfigurationController::class, 'index']);
Route::post('config/', [ConfigurationController::class, 'store']);
