<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AddingController;
use App\Http\Controllers\GraddingController;
use App\Http\Controllers\MandorController;
use App\Http\Controllers\DataPekerjaController;

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
Route::post('login-user', [ApiController::class, 'login']);
Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);
Route::post('cari',  [ApiController::class, 'searchpartai']);
Route::post('cari-transaksi',  [ApiController::class, 'searchtransaksi']);
Route::post('listNama',  [ApiController::class, 'listNama']);


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    // service adding start
    Route::get('loaddata', [AddingController::class, 'index']);
    Route::post('viewadding/{id}', [AddingController::class, 'show']);
    Route::post('createadding', [AddingController::class, 'store']);
    Route::post('update/{adding}',  [AddingController::class, 'update']);
    Route::post('delete/{adding}',  [AddingController::class, 'destroy']);
    // end
    // service gradding start
    Route::get('loadgradding', [GraddingController::class, 'index']);
    Route::post('viewgradding/{id}', [GraddingController::class, 'show']);
    Route::post('creategradding', [GraddingController::class, 'store']);
    Route::post('updategradding/{gradding}',  [GraddingController::class, 'update']);
    Route::post('deletegradding/{gradding}',  [GraddingController::class, 'destroy']);
    // end
   // service mandor start
    Route::get('loadmandor', [MandorController::class, 'index']);
    Route::post('viewgmandor/{id}', [MandorController::class, 'show']);
    Route::post('createmandor', [MandorController::class, 'store']);
    Route::post('updatemandor/{mandor}',  [MandorController::class, 'update']);
    Route::post('deletemandor/{mandor}',  [MandorController::class, 'destroy']);
    // end

    // service datapekerja start
    Route::get('loadpekerja', [DataPekerjaController::class, 'index']);
    Route::post('viewpekerja/{id}', [DataPekerjaController::class, 'show']);
    Route::post('createpekerja', [DataPekerjaController::class, 'store']);
    Route::post('updatepekerja/{datapekerja}',  [DataPekerjaController::class, 'update']);
    Route::post('deletepekerja/{datapekerja}',  [DataPekerjaController::class, 'destroy']);
    // end
});
