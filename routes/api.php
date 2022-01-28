<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AddingController;
use App\Http\Controllers\GraddingController;
use App\Http\Controllers\MandorController;
use App\Http\Controllers\DataPekerjaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KoreksiController;
use App\Http\Controllers\PengeringPertamaController;
use App\Http\Controllers\MasterRumahWaletController;


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
Route::post('cari-transaksi-proses',  [ApiController::class, 'searchtransaksiproses']);
Route::post('cari-transaksi-koreksi',  [ApiController::class, 'searchtransaksikoreksi']);
Route::post('cari-transaksi-mandor',  [ApiController::class, 'searchmandorselesai']);
Route::get('get-all-adding',  [ApiController::class, 'allAdding']);
Route::get('get-all-gradding',  [ApiController::class, 'allGradding']);
Route::get('get-all-mandor',  [ApiController::class, 'allMandor']);
Route::get('get-all-rumahwalet',  [ApiController::class, 'allRumahWalet']);
Route::get('filter-date-adding',  [ApiController::class, 'filterbyDateAdding']);
Route::get('filter-date-gradding',  [ApiController::class, 'filterbyDateGradding']);
Route::get('filter-date-mandor',  [ApiController::class, 'filterbyDateMandor']);
Route::post('view-adding/{id}',  [ApiController::class, 'showadding']);
Route::post('view-gradding/{id}',  [ApiController::class, 'showgradding']);
Route::post('view-mandor/{id}',  [ApiController::class, 'showmandor']);




Route::post('listNama',  [ApiController::class, 'listNama']);
 // service data Users start
Route::get('loadUsers', [ApiController::class, 'index']);
Route::post('viewusers/{id}', [ApiController::class, 'show']);
Route::post('updateusers/{users}',  [ApiController::class, 'update']);
Route::post('updatepassword/{users}',  [ApiController::class, 'updatepassword']);
Route::post('deleteusers/{users}',  [ApiController::class, 'destroy']);
Route::post('loadrole',  [ApiController::class, 'indexrole']);
// end


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

    // service koreksi start
    Route::get('loadkoreksi', [KoreksiController::class, 'index']);
    Route::post('viewkoreksi/{id}', [KoreksiController::class, 'show']);
    Route::post('createkoreksi', [KoreksiController::class, 'store']);
    Route::post('updatekoreksi/{koreksi}',  [KoreksiController::class, 'update']);
    Route::post('deletekoreksi/{koreksi}',  [KoreksiController::class, 'destroy']);
    // end

    // service koreksi start
    Route::get('loaddry-1', [PengeringPertamaController::class, 'index']);
    Route::post('viewdry-1/{id}', [PengeringPertamaController::class, 'show']);
    Route::post('createdry-1', [PengeringPertamaController::class, 'store']);
    Route::post('updatedry-1/{dry-1}',  [PengeringPertamaController::class, 'update']);
    Route::post('deletedry-1/{dry-1}',  [PengeringPertamaController::class, 'destroy']);
    // end

    // service master rumah walet start
    Route::get('loadrumahwalet', [MasterRumahWaletController::class, 'index']);
    Route::post('viewrumahwalet/{id}', [MasterRumahWaletController::class, 'show']);
    Route::post('createrumahwalet', [MasterRumahWaletController::class, 'store']);
    Route::post('updaterumahwalet/{rumahwalet}',  [MasterRumahWaletController::class, 'update']);
    Route::post('deleteperumahwalet/{rumahwalet}',  [MasterRumahWaletController::class, 'destroy']);
    
    // end
});
