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
use App\Http\Controllers\MoldingController;
use App\Http\Controllers\PengeringKeduaController;
use App\Http\Controllers\pemanasController;
use App\Http\Controllers\LookupController;
use App\Http\Controllers\GradingAkhirController;
use App\Http\Controllers\GradingAkhirNewController;
use App\Http\Controllers\packingController;


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
Route::post('cari-transaksi-dry-pertama',  [ApiController::class, 'searchtransaksidrypertama']);
Route::post('cari-transaksi-molding',  [ApiController::class, 'searchtransaksimolding']);
Route::post('cari-transaksi-dry-kedua',  [ApiController::class, 'searchtransaksidrykedua']);
Route::post('cari-type', [LookupController::class, 'searchlookup']);
Route::post('cari-kode-mandor',  [ApiController::class, 'searchkodemandor']);
Route::post('cari-gradeakhir',  [ApiController::class, 'searchtransaksigradeakhir']);
Route::post('cari-gradingakhir',  [ApiController::class, 'searchGradingAkhir']);
Route::post('cari-ga-streaming',  [ApiController::class, 'searchKodeGAStreaming']);
Route::post('cari-ga-packing',  [ApiController::class, 'searchKodeGAPacking']);
Route::post('filter-jenis-gradeakhir',  [ApiController::class, 'filterJenisGradeakhir']);
Route::get('filter-ga-traceability',  [ApiController::class, 'filterSeriGradeAkhir']);



//Get all data
Route::get('get-all-adding',  [ApiController::class, 'allAdding']);
Route::get('get-all-gradding',  [ApiController::class, 'allGradding']);
Route::get('get-all-mandor',  [ApiController::class, 'allMandor']);
Route::get('get-all-koreksi',  [ApiController::class, 'allKoreksi']);
Route::get('get-all-drypertama',  [ApiController::class, 'allDrypertama']);
Route::get('get-all-molding',  [ApiController::class, 'allMolding']);
Route::get('get-all-drykedua',  [ApiController::class, 'allDrykedua']);
Route::get('get-all-rumahwalet',  [ApiController::class, 'allRumahWalet']);
Route::get('get-all-stock',  [ApiController::class, 'getLoadDrykedua']);
Route::get('get-all-gradeakhir',  [ApiController::class, 'allGradAkhir']);
Route::get('get-all-streaming',  [ApiController::class, 'allStreaming']);
Route::get('get-all-packing',  [ApiController::class, 'allPacking']);
Route::get('get-all-tracebility',  [ApiController::class, 'getAllTracebility']);


// end
// Filter by date 
Route::get('filter-date-adding',  [ApiController::class, 'filterbyDateAdding']);
Route::get('filter-date-gradding',  [ApiController::class, 'filterbyDateGradding']);
Route::get('filter-date-mandor',  [ApiController::class, 'filterbyDateMandor']);
Route::get('filter-date-koreksi',  [ApiController::class, 'filterbyDateKoreksi']);
Route::get('filter-date-drypertama',  [ApiController::class, 'filterbyDateDrypertama']);
Route::get('filter-date-drykedua',  [ApiController::class, 'filterbyDateDrykedua']);
Route::get('filter-date-molding',  [ApiController::class, 'filterbyDateMolding']);
Route::get('filter-date-gradeakhir',  [ApiController::class, 'filterbyDateGradingakhir']);
Route::get('filter-date-streaming',  [ApiController::class, 'filterbyDateStreaming']);
Route::get('filter-date-packing',  [ApiController::class, 'filterbyDatePacking']);

// end
// view data 
Route::post('view-adding/{id}',  [ApiController::class, 'showadding']);
Route::post('view-gradding/{id}',  [ApiController::class, 'showgradding']);
Route::post('view-mandor/{id}',  [ApiController::class, 'showmandor']);
Route::post('view-koreksi/{id}',  [ApiController::class, 'showkoreksi']);
Route::post('view-drypertama/{id}',  [ApiController::class, 'showdrypertama']);
Route::post('view-laporanmolding/{id}',  [ApiController::class, 'showmolding']);
Route::post('view-laporandrykedua/{id}',  [ApiController::class, 'showdrykedua']);
Route::post('view-showgradingakhir/{id}',  [ApiController::class, 'showgradingakhir']);
Route::post('view-showstreaming/{id}',  [ApiController::class, 'showstreaming']);
Route::post('view-showpacking/{id}',  [ApiController::class, 'showpacking']);

// end
// Lookup
Route::post('lookup',  [LookupController::class, 'store']);
// getcount
Route::post('getCount',  [ApiController::class, 'getCount']);
// get all nama pegawai
Route::post('listNama',  [ApiController::class, 'listNama']);
 // service data Users start
Route::get('loadUsers', [ApiController::class, 'index']);
Route::post('viewusers/{id}', [ApiController::class, 'show']);
Route::post('updateusers/{users}',  [ApiController::class, 'update']);
Route::post('updatepassword/{users}',  [ApiController::class, 'updatepassword']);
Route::post('deleteusers/{users}',  [ApiController::class, 'destroy']);
Route::post('loadrole',  [ApiController::class, 'indexrole']);
// end
// Filter Laporan by Kode Kode Partai
Route::post('filter-kp-adding',  [ApiController::class, 'filterKodepartaiAdding']);
Route::post('filter-kp-grading',  [ApiController::class, 'filterKodepartaiGrading']);
Route::post('filter-kp-mandor',  [ApiController::class, 'filterKodepartaiMandor']);
Route::post('filter-kp-koreksi',  [ApiController::class, 'filterKodepartaiKoreksi']);
Route::post('filter-kp-drypertama',  [ApiController::class, 'filterKodepartaiDryPertama']);
Route::post('filter-kp-molding',  [ApiController::class, 'filterKodepartaiMolding']);
Route::post('filter-kp-drykedua',  [ApiController::class, 'filterKodepartaiDryKedua']);
Route::post('filter-kp-gradeakhir',  [ApiController::class, 'filterKodepartaiGradeakhir']);

// End
// Penjumlahan grade akhir

Route::post('kurangi-stock-ga',  [ApiController::class, 'kurangistock']);
Route::post('restore-stock-ga',  [ApiController::class, 'restorestock']);


// End

// Middleware using token JWT
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
    Route::post('delete',  [AddingController::class, 'destroy']);
    // end

    // service gradding start
    Route::get('loadgradding', [GraddingController::class, 'index']);
    Route::post('viewgradding/{id}', [GraddingController::class, 'show']);
    Route::post('creategradding', [GraddingController::class, 'store']);
    Route::post('updategradding/{gradding}',  [GraddingController::class, 'update']);
    Route::post('deletegradding',  [GraddingController::class, 'destroy']);
    // end

    // service mandor start
    Route::post('loadmandor', [MandorController::class, 'index']);
    Route::post('viewgmandor/{id}', [MandorController::class, 'show']);
    Route::post('createmandor', [MandorController::class, 'store']);
    Route::post('updatemandor/{mandor}',  [MandorController::class, 'update']);
    Route::post('deletemandor/{mandor}',  [MandorController::class, 'destroy']);
    Route::post('deletemandorend',  [MandorController::class, 'destroyend']);
    // end

    // service datapekerja start
    Route::get('loadpekerja', [DataPekerjaController::class, 'index']);
    Route::post('viewpekerja/{id}', [DataPekerjaController::class, 'show']);
    Route::post('createpekerja', [DataPekerjaController::class, 'store']);
    Route::post('updatepekerja/{datapekerja}',  [DataPekerjaController::class, 'update']);
    Route::post('deletepekerja/{datapekerja}',  [DataPekerjaController::class, 'destroy']);
    // end

    // service koreksi start
    Route::post('loadkoreksi', [KoreksiController::class, 'index']);
    Route::post('viewkoreksi/{id}', [KoreksiController::class, 'show']);
    Route::post('createkoreksi', [KoreksiController::class, 'store']);
    Route::post('updatekoreksi/{koreksi}',  [KoreksiController::class, 'update']);
    Route::post('deletekoreksi',  [KoreksiController::class, 'destroy']);
    // end

    // service Dry pertama start
    Route::get('loaddry-1', [PengeringPertamaController::class, 'index']);
    Route::post('viewdry-1/{id}', [PengeringPertamaController::class, 'show']);
    Route::post('createdry-1', [PengeringPertamaController::class, 'store']);
    Route::post('updatedry-1/{drypertama}',  [PengeringPertamaController::class, 'update']);
    Route::post('deletedry-1',  [PengeringPertamaController::class, 'destroy']);
    // end

    // service master rumah walet start
    Route::get('loadrumahwalet', [MasterRumahWaletController::class, 'index']);
    Route::post('viewrumahwalet/{id}', [MasterRumahWaletController::class, 'show']);
    Route::post('createrumahwalet', [MasterRumahWaletController::class, 'store']);
    Route::post('updaterumahwalet/{rumahwalet}',  [MasterRumahWaletController::class, 'update']);
    Route::post('deleteperumahwalet/{rumahwalet}',  [MasterRumahWaletController::class, 'destroy']);
    // end

    // service Molding start
    Route::get('load-molding', [MoldingController::class, 'index']);
    Route::post('view-molding/{id}', [MoldingController::class, 'show']);
    Route::post('create-molding', [MoldingController::class, 'store']);
    Route::post('update-molding/{molding}',  [MoldingController::class, 'update']);
    Route::post('delete-molding',  [MoldingController::class, 'destroy']);
    // end

    // service Dry Kedua start
    Route::get('load-dry-2', [PengeringKeduaController::class, 'index']);
    Route::post('view-dry-2/{id}', [PengeringKeduaController::class, 'show']);
    Route::post('create-dry-2', [PengeringKeduaController::class, 'store']);
    Route::post('update-dry-2/{drykedua}',  [PengeringKeduaController::class, 'update']);
    Route::post('delete-dry-2/{drykedua}',  [PengeringKeduaController::class, 'destroy']);
    // end

     // service Pemanas start
    Route::get('load-pemanas', [pemanasController::class, 'index']);
    Route::post('view-pemanas/{id}', [pemanasController::class, 'show']);
    Route::post('create-pemanas', [pemanasController::class, 'store']);
    Route::post('update-pemanas/{pemanas}',  [pemanasController::class, 'update']);
    Route::post('delete-pemanas/{pemanas}',  [pemanasController::class, 'destroy']);
    // end

    // service Packing start
    Route::get('load-packing', [packingController::class, 'index']);
    Route::post('view-packing/{id}', [packingController::class, 'show']);
    Route::post('create-packing', [packingController::class, 'store']);
    Route::post('update-packing/{packing}',  [packingController::class, 'update']);
    Route::post('delete-packing/{packing}',  [packingController::class, 'destroy']);
    // end

        // service datapekerja start
    Route::get('load-lookup', [LookupController::class, 'index']);
    Route::post('view-lookup/{id}', [LookupController::class, 'show']);
    Route::post('create-lookup', [LookupController::class, 'store']);
    Route::post('update-lookup/{id}',  [LookupController::class, 'update']);
    Route::post('delete-lookup/{id}',  [LookupController::class, 'destroy']);
    // end

    Route::post('post-grading-akhir',  [GradingAkhirController::class, 'storeInsert']);
    Route::get('load-ga', [GradingAkhirController::class, 'index']);

    Route::post('post-grading-akhir-new',  [GradingAkhirNewController::class, 'storeInsert']);
    Route::get('load-ga-new', [GradingAkhirNewController::class, 'index']);


});
