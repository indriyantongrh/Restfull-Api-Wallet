<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\gradding;
use App\pemanas;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class pemanasController extends Controller
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
        return $this->user
            ->pemanas()
            ->orderBy('id', 'DESC')->get();
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
        $data = $request->only('id', 'user_id', 'kode_transaksi_grading','temperatur_pre_heating', 'waktu_pre_heating','temperatur_tot', 'waktu_tot','jumlah_keping', 'jumlah_pending_keping', 'status', 'tanggal_proses', 'keterangan');
        $kode_transaksiExist = pemanas::where('kode_transaksi_grading', '=', $request->input('kode_transaksi_grading'))->first();
        $validator = Validator::make($data, [
            // 'no_register' => 'required',
            // 'kode_partai' => 'required'
        ]);

        //Send failed response if request is not valid
        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->messages()], 200);
        // }

        // if($kode_transaksiExist === null){
            //Request is valid, create new product
            $pemanas= $this->user->pemanas()->create([
                'user_id' => $request->user_id,
                'kode_transaksi_grading' => $request->kode_transaksi_grading,
                'temperatur_pre_heating' => $request->temperatur_pre_heating,
                'waktu_pre_heating' => $request->waktu_pre_heating,
                'temperatur_tot' => $request->temperatur_tot,
                'waktu_tot' => $request->waktu_tot,
                'tanggal_proses' => $request->tanggal_proses,
                'jumlah_keping' => $request->jumlah_keping,
                'jumlah_pending_keping' => $request->jumlah_pending_keping,
                'status' => $request->status,
                'keterangan' => $request->keterangan
            ]);

            //Product created, return success response
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambah!',
                'data' => $pemanas
            ], Response::HTTP_OK);
        // }else{
        //     return response()->json([
        //     'success' => false,
        //     'message' => 'Data Transaksi sudah di tambah sebelum nya!',
        // ], Response::HTTP_OK);
        // }

       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\adding  $adding
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pemanas = $this->user->pemanas()->find($id);
    
        if (!$pemanas) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data not found.'
            ], 400);
        }else{
           
            return response()->json([
               $pemanas
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
    public function edit(pemanas $pemanas)
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
    public function update(Request $request, pemanas $pemanas)
    {
        //Validate data
        $data = $request->only('id', 'user_id', 'kode_transaksi_grading','temperatur_pre_heating', 'waktu_pre_heating','temperatur_tot', 'waktu_tot', 'tanggal_proses','jumlah_keping', 'jumlah_pending_keping', 'status',  'keterangan');

        $validator = Validator::make($data, [
        ]);

        

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $pemanas = $pemanas->update([
           'user_id' => $request->user_id,
           'kode_transaksi_grading' => $request->kode_transaksi_grading,
            'temperatur_pre_heating' => $request->temperatur_pre_heating,
            'waktu_pre_heating' => $request->waktu_pre_heating,
            'temperatur_tot' => $request->temperatur_tot,
            'waktu_tot' => $request->waktu_tot,
            'tanggal_proses' => $request->tanggal_proses,
            'jumlah_keping' => $request->jumlah_keping,
            'jumlah_pending_keping' => $request->jumlah_pending_keping,
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'data berhasil diupdate',
            'data' => $pemanas
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function destroy(pemanas $pemanas)
    {
        $pemanas->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully'
        ], Response::HTTP_OK);
    }
}

