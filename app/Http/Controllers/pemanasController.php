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
            ->get();
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
        $data = $request->only('user_id', 'adding_id','mandor_id', 'gradding_id','koreksi_id', 'dry_pertama_id', 'molding_id', 'dry_kedua_id','kode_partai','no_register', 'tanggal_proses', 'suhu','waktu', 'kode_transaksi');
        $kode_transaksiExist = pemanas::where('kode_transaksi', '=', $request->input('kode_transaksi'))->first();
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
            $pemanas= $this->user->pemanas()->create([
                'user_id' => $request->user_id,
                'adding_id' => $request->adding_id,
                'gradding_id' => $request->gradding_id,
                'mandor_id' => $request->mandor_id,
                'koreksi_id' => $request->koreksi_id,
                'dry_pertama_id' => $request->dry_pertama_id,
                'dry_kedua_id' => $request->dry_kedua_id,
                'molding_id' => $request->molding_id,
                'kode_partai' => $request->kode_partai,
                'kode_transaksi' => $request->kode_transaksi,
                'no_register' => $request->no_register,
                'tanggal_proses' => $request->tanggal_proses,
                'suhu' => $request->suhu,
                'waktu' => $request->waktu,
           
            ]);

            //Product created, return success response
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambah!',
                'data' => $pemanas
            ], Response::HTTP_OK);
        }else{
            return response()->json([
            'success' => false,
            'message' => 'Data Transaksi sudah di tambah sebelum nya!',
        ], Response::HTTP_OK);
        }

       
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
            $gradding = gradding::where('id', $pemanas->gradding_id)->first();

            return response()->json([
                'id' => $pemanas->id,
                'adding_id' => $pemanas->adding_id,
                'gradding_id' => $pemanas->gradding_id,
                'mandor_id' => $pemanas->mandor_id,
                'koreksi_id' => $pemanas->koreksi_id,
                'dry_pertama_id' => $pemanas->dry_pertama_id,
                'dry_kedua_id' => $pemanas->dry_kedua_id,
                'molding_id' => $pemanas->molding_id,
                'kode_transaksi' => $pemanas->kode_transaksi,
                'kode_partai' => $pemanas->kode_partai,
                'no_register' => $pemanas->no_register,
                'tanggal_proses' => $pemanas->tanggal_proses,
                'suhu' => $pemanas->suhu,
                'waktu' => $pemanas->waktu,
            
                'user_id' => $pemanas->user_id,
            
                'jenis_grade' => $gradding->jenis_grade

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
        $data = $request->only('user_id', 'adding_id','mandor_id', 'gradding_id','koreksi_id', 'dry_pertama_id', 'molding_id', 'dry_kedua_id','kode_partai','no_register', 'tanggal_proses', 'suhu','waktu', 'kode_transaksi',);

        $validator = Validator::make($data, [
        ]);

        

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $pemanas = $pemanas->update([
           'user_id' => $request->user_id,
                'adding_id' => $request->adding_id,
                'gradding_id' => $request->gradding_id,
                'mandor_id' => $request->mandor_id,
                'koreksi_id' => $request->koreksi_id,
                'dry_pertama_id' => $request->dry_pertama_id,
                'dry_kedua_id' => $request->dry_kedua_id,
                'molding_id' => $request->molding_id,
                'kode_partai' => $request->kode_partai,
                'kode_transaksi' => $request->kode_transaksi,
                'no_register' => $request->no_register,
                'tanggal_proses' => $request->tanggal_proses,
                'suhu' => $request->suhu,
                'waktu' => $request->waktu
                
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

