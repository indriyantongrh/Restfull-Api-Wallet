<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AddingController;
use App\Http\Controllers\GraddingController;

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

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    // service adding start
    Route::get('loaddata', [AddingController::class, 'index']);
    Route::get('viewadding/{id}', [AddingController::class, 'show']);
    Route::post('createadding', [AddingController::class, 'store']);
    Route::put('update/{adding}',  [AddingController::class, 'update']);
    Route::post('delete/{adding}',  [AddingController::class, 'destroy']);
    // end
    // service gradding start
    Route::get('loadgradding', [GraddingController::class, 'index']);
    Route::get('viewgradding/{id}', [GraddingController::class, 'show']);
    Route::post('creategradding', [GraddingController::class, 'store']);
    Route::put('updategradding/{gradding}',  [GraddingController::class, 'update']);
    Route::post('deletegradding/{gradding}',  [GraddingController::class, 'destroy']);
    // end

});
