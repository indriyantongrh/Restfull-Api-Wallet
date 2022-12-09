<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\gradding;
use App\packing;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class packingController extends Controller
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
     
        
        return 
        DB::table('packing')
        ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
        ->select(
            'transaksi_data_grading_akhir.name_jenis_garding',
            'packing.kode_transaksi_grading',
            'packing.jenis_kemasan',
            'packing.koli',
            'packing.tanggal_packing',
            'packing.tanggal_pengiriman',
            'packing.updated_at',
            'packing.created_at',
            'packing.jenis_kemasan',
            'packing.box',
            )
            ->distinct('packing.kode_transaksi_grading')->orderBy('packing.created_at', 'DESC')->get();
   
        
        // DB::table('packing')
        //         ->select(
        //             'user_id',
        //             'grade_akhir_id',
        //             'created_at',
        //             'id',
        //             'jenis_kemasan',
        //             'koli',
        //             'tanggal_packing',
        //             'tanggal_pengiriman',
        //             'updated_at',
        //             'kode_transaksi_grading',
        //             'jenis_kemasan',
        //             'box')
        //         ->groupBy(
        //             'kode_transaksi_grading')
        //         // ->havingRaw('COUNT(kode_transaksi_grading) > 1')
        //         ->get();
        // $this->user
        //         ->packing()
        //         ->groupBy('kode_transaksi_grading')->get();
        
        // packing::distinct()
        // ->get([
        //     'kode_transaksi_grading',
        //     'id',
        //     'jenis_kemasan',
        //     'koli',
        //     'box',
        //     'tanggal_packing',
        //     'tanggal_pengiriman'
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //Validate data
        $data = $request->only('user_id', 'grade_akhir_id','kode_transaksi_grading','jenis_kemasan', 'box','koli', 'tanggal_packing', 'tanggal_pengiriman');
        $kode_transaksiExist = packing::where('kode_transaksi_grading', '=', $request->input('kode_transaksi_grading'))->first();
        $validator = Validator::make($data, [
            // 'no_register' => 'required',
            // 'kode_partai' => 'required'
        ]);

        //Send failed response if request is not valid
        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->messages()], 200);
        // }

        if($kode_transaksiExist === null){
            //Request is valid, create new product
            
            $dataResult= $this->user->packing()->create([
                'user_id' => $request->user_id,
                'grade_akhir_id' => $request->grade_akhir_id,
                'kode_transaksi_grading' => $request->kode_transaksi_grading,
                'jenis_kemasan' => $request->jenis_kemasan,
                'box' => $request->box,
                'koli' => $request->koli,
                'tanggal_packing' => $request->tanggal_packing,
                'tanggal_pengiriman' => $request->tanggal_pengiriman
            ]);

            //Product created, return success response
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambah!',
                'data' => $dataResult
            ], Response::HTTP_OK);
        }else{
            return response()->json([
            'success' => false,
            'message' => 'Data Transaksi sudah di tambah sebelum nya!',
        ], Response::HTTP_OK);
        }

       
    }

    public function storeInsert(Request $request)
     {

         $data= $request->get('data');
         $response =  packing::insert(json_decode($data, true)); // Eloquent approach
          return response()->json([
             'code' => 1,
             'success' => true,
             'message' => 'Data berhasil ditambah!',
             'data' => $data
         ], Response::HTTP_OK);
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\adding  $adding
     * @return \Illuminate\Http\Response
     */
    public function show($kode_transaksi_grading)
    {
        // $data = $this->user->packing()->find($kode_transaksi_grading);
        $data = $this->user->packing()->where('kode_transaksi_grading', $kode_transaksi_grading)->first();
    
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data not found.'
            ], 400);
        }else{
           
            return response()->json([
               $data
            ], 200);
        }
    
        // return $drypertama;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function edit(packing $packing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, packing $packing, $kode_transaksi_grading)
    {
        //Validate data
        $data = $request->only('user_id', 'grade_akhir_id','kode_transaksi_grading','jenis_kemasan', 'box','koli', 'tanggal_packing', 'tanggal_pengiriman');

        $validator = Validator::make($data, [
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $packing = packing::where('kode_transaksi_grading', $kode_transaksi_grading ) ->update([
                'user_id' => $request->user_id,
                'grade_akhir_id' => $request->grade_akhir_id,
                'kode_transaksi_grading' => $request->kode_transaksi_grading,
                'jenis_kemasan' => $request->jenis_kemasan,
                'box' => $request->box,
                'koli' => $request->koli,
                'tanggal_packing' => $request->tanggal_packing,
                'tanggal_pengiriman' => $request->tanggal_pengiriman
        ]);

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'data berhasil diupdate',
            'data' => $packing
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode_transaksi_grading)
    {
        $data = $this->user->packing()->where('kode_transaksi_grading', $kode_transaksi_grading)->delete();
        // $data->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully',
            'ddd' => $data
        ], Response::HTTP_OK);
    }

    
}
