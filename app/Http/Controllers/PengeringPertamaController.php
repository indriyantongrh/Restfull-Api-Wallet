<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\drypertama;
use App\mandor;
use App\gradding;
use App\molding;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class PengeringPertamaController extends Controller
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
            ->drypertama()
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
        $data = $request->only('user_id', 'adding_id','mandor_id', 'gradding_id','koreksi_id','kode_partai','no_register', 'tanggal_proses', 'jumlah_sbw','jumlah_keping', 'jumlah_box', 'kode_transaksi', 'status');
        $kode_transaksiExist = drypertama::where('kode_transaksi', '=', $request->input('kode_transaksi'))->first();
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
            $drypertama= $this->user->drypertama()->create([
                'user_id' => $request->user_id,
                'adding_id' => $request->adding_id,
                'gradding_id' => $request->gradding_id,
                'mandor_id' => $request->mandor_id,
                'koreksi_id' => $request->koreksi_id,
                'kode_partai' => $request->kode_partai,
                'kode_transaksi' => $request->kode_transaksi,
                'no_register' => $request->no_register,
                'tanggal_proses' => $request->tanggal_proses,
                'jumlah_sbw' => $request->jumlah_sbw,
                'jumlah_box' => $request->jumlah_box,
                'jumlah_keping' => $request->jumlah_keping,
                'status' => $request->status
            ]);

            //Product created, return success response
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambah!',
                'data' => $drypertama
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
        $drypertama = $this->user->drypertama()->find($id);
    
        if (!$drypertama) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data not found.'
            ], 400);
        }else{
            $gradding = gradding::where('id', $drypertama->gradding_id)->first();

            return response()->json([
                'id' => $drypertama->id,
                'adding_id' => $drypertama->adding_id,
                'gradding_id' => $drypertama->gradding_id,
                'mandor_id' => $drypertama->mandor_id,
                'koreksi_id' => $drypertama->koreksi_id,
                'kode_transaksi' => $drypertama->kode_transaksi,
                'kode_partai' => $drypertama->kode_partai,
                'no_register' => $drypertama->no_register,
                'tanggal_proses' => $drypertama->tanggal_proses,
                'jumlah_box' => $drypertama->jumlah_box,
                'jumlah_sbw' => $drypertama->jumlah_sbw,
                'jumlah_keping' => $drypertama->jumlah_keping,
                'user_id' => $drypertama->user_id,
                'status' => $drypertama->status,
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
    public function edit(drypertama $drypertama)
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
    public function update(Request $request, drypertama $drypertama)
    {
        //Validate data
        $data = $request->only('user_id', 'adding_id','mandor_id', 'gradding_id','koreksi_id','kode_partai','no_register', 'tanggal_proses', 'jumlah_sbw','jumlah_box', 'jumlah_keping','kode_transaksi', 'status');

        $validator = Validator::make($data, [
        ]);

        

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $drypertama = $drypertama->update([
           'user_id' => $request->user_id,
                'adding_id' => $request->adding_id,
                'gradding_id' => $request->gradding_id,
                'mandor_id' => $request->mandor_id,
                'koreksi_id' => $request->koreksi_id,
                'kode_partai' => $request->kode_partai,
                'kode_transaksi' => $request->kode_transaksi,
                'no_register' => $request->no_register,
                'tanggal_proses' => $request->tanggal_proses,
                'jumlah_sbw' => $request->jumlah_sbw,
                'jumlah_box' => $request->jumlah_box,
                'jumlah_keping' => $request->jumlah_keping,
                'status' => $request->status
        ]);

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'data berhasil diupdate',
            'data' => $drypertama
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
   public function destroy(drypertama $drypertama, Request $request)
    {
        $data = $request->get('data');
        $id = $request->get('id');
        $getDatas =  molding::where('kode_transaksi', 'like', "{$data}")
                        ->first();
        if ($getDatas == null ){
            drypertama::where('id', 'like', "{$id}")->delete();
            return response()->json([
                        'success' => true,
                        'pesancari' => 'data tidak ditemukan',
                        'message' => 'data berhasil di hapus'
                    ], Response::HTTP_OK);
        }else{
            return response()->json([
                        'success' => false,
                        'pesancari' => 'data  ditemukan',
                        'message' => 'data tidak dapat dihapus karena sudha di proses'

                    ], Response::HTTP_OK);

        }
    }
}
