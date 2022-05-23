<?php

namespace App\Http\Controllers;

 use Illuminate\Http\Request;
 use App\adding;
 use App\gradingakhir;
 use App\dryedua;
 use JWTAuth;
 use Tymon\JWTAuth\Exceptions\JWTException;
 use Symfony\Component\HttpFoundation\Response;
 use Illuminate\Support\Facades\Validator;


 class GradingAkhirController extends Controller
 {
     protected $user;

     public function __construct()
     {
         $this->user = JWTAuth::parseToken()->authenticate();
     }

      /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function index()
         {
             // return $this->user
             //     ->adding()
             //     ->get();

             $gradingakhir= $this->user->gradingakhir()->orderBy('id', 'DESC')->get();
             return response()->json([

                 'success' => true,
                 'message' => 'Load data',
                 'data' => $gradingakhir
             ], Response::HTTP_OK);
         }
     public function store(Request $request)
     {


         //Validate data
         $data = $request->only('id_user','id_dry_kedua', 'kode_transaksi', 'kode_register','kode_partai', 'jumlah_saldo', 'jumlah_sbw_grading', 'id_jenis_garding', 'name_jenis_garding', );
         $validator = Validator::make($data, [
             'no_register' => 'required',
             'kode_partai' => 'required'
         ]);

         //Send failed response if request is not valid
         if ($validator->fails()) {
             return response()->json(['error' => $validator->messages()], 200);
         }

         //Request is valid, create new product
         gradingakhir::insert(json_decode($data,true));
         //Product created, return success response
         return response()->json([
             'code' => 1,
             'success' => true,
             'message' => 'Data berhasil ditambah!',
             'data' => $adding
         ], Response::HTTP_OK);
     }

     public function storeInsert(Request $request)
     {

         $data= $request->get('data');
         $response =  gradingakhir::insert(json_decode($data, true)); // Eloquent approach
          return response()->json([
             'code' => 1,
             'success' => true,
             'message' => 'Data berhasil ditambah!',
             'data' => $data
         ], Response::HTTP_OK);
     }


 }

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\adding;
// use App\gradingakhir;
// use JWTAuth;
// use Tymon\JWTAuth\Exceptions\JWTException;
// use Symfony\Component\HttpFoundation\Response;
// use Illuminate\Support\Facades\Validator;


// class GradingAkhirController extends Controller
// {
//     protected $user;
 
//     public function __construct()
//     {
//         $this->user = JWTAuth::parseToken()->authenticate();
//     }

//      /**
//      * Display a listing of the resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function index()
//         {
//             // return $this->user
//             //     ->adding()
//             //     ->get();

//             $gradingakhir= $this->user->gradingakhir()->orderBy('id', 'DESC')->get();
//             return response()->json([
            
//                 'success' => true,
//                 'message' => 'Load data',
//                 'data' => $gradingakhir
//             ], Response::HTTP_OK);
//         }
//     public function store(Request $request)
//     {

        
//         //Validate data
//         $data = $request->only('id_user','id_dry_kedua', 'kode_transaksi', 'kode_register','kode_partai', 'jumlah_saldo', 'jumlah_sbw_grading', 'id_jenis_garding', 'name_jenis_garding', );
//         $validator = Validator::make($data, [
//             'no_register' => 'required',
//             'kode_partai' => 'required'
//         ]);

//         //Send failed response if request is not valid
//         if ($validator->fails()) {
//             return response()->json(['error' => $validator->messages()], 200);
//         }

//         //Request is valid, create new product
//         gradingakhir::insert(json_decode($data,true));
//         //Product created, return success response
//         return response()->json([
//             'code' => 1,
//             'success' => true,
//             'message' => 'Data berhasil ditambah!',
//             'data' => $adding
//         ], Response::HTTP_OK);
//     }

//     public function storeInsert(Request $request)
//     {
//         $data= $request->get('data');
//         $response =  gradingakhir::insert(json_decode($data, true)); // Eloquent approach
//          return response()->json([
//             'code' => 1,
//             'success' => true,
//             'message' => 'Data berhasil ditambah!',
//             'data' => $data
//         ], Response::HTTP_OK);
//         // $data= $request->get('data');
//         // $response =  gradingakhir::insert(json_decode($data, true)); // Eloquent approach
//         //  return response()->json([;
//         //     'code' => 1,
//         //     'success' => true,
//         //     'message' => 'Data berhasil ditambah!',
//         //     'data' => $data
//         // ], Response::HTTP_OK);
//     }
//     //  public function destroy(dryedua $dryedua,  Request $request)
//     // {
//     //         $getData = $this->user->mandor()->find($dryedua)->first();
//     //         $jumlahsaldo = $getData->jumlah_sbw;
//     //         $kepingsaldo = $getData->jumlah_keping;
//     //         $idgrading = $getData->gradding_id;

//     //         // $gradding = gradding::find($idgrading);
//     //         $gradding = gradding::where('id', 'like', "{$idgrading}")->first();
//     //         $gradding->jmlh_sbw_saldo = ($gradding->jmlh_sbw_saldo + $jumlahsaldo);
//     //         $gradding->jmlh_keping_saldo = ($gradding->jmlh_keping_saldo + $kepingsaldo);
//     //         $gradding->update();

//     //          $mandor->delete();
            

//     //     return response()->json([
//     //         'success' => true,
//     //         'message' => 'data  deleted successfully',
//     //         'mandorid' => $mandorGet,
//     //         'jmlhsaldo' => $jumlahsaldo,
//     //         'kepingsalso' => $kepingsaldo,
//     //         'idgrading' => $idgrading,
//     //         'gradding' => $gradding,
//     //         'data' => $jumlahsaldo,
//     //         'data' => $jumlahsaldo,
//     //     ], Response::HTTP_OK);
//     // }

   
// }
