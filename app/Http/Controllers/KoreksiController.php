<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\koreksi;
use App\mandor;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class KoreksiController extends Controller
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
            ->koreksi()
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
        $data = $request->only('user_id', 'adding_id', 'gradding_id','mandor_id', 'kode_transaksi', 'kode_partai', 'no_register', 'tanggal_proses', 'jumlah_sbw', 'jumlah_box', 'jumlah_keping', 'progres_koreksi', 'jumlah_pending','status');
        $kode_transaksiExist = koreksi::where('kode_transaksi', '=', $request->input('kode_transaksi'))->first();
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
            $koreksi= $this->user->koreksi()->create([
                'user_id' => $request->user_id,
                'adding_id' => $request->adding_id,
                'gradding_id' => $request->gradding_id,
                'mandor_id' => $request->mandor_id,
                'kode_partai' => $request->kode_partai,
                'kode_transaksi' => $request->kode_transaksi,
                'no_register' => $request->no_register,
                'tanggal_proses' => $request->tanggal_proses,
                'jumlah_sbw' => $request->jumlah_sbw,
                'jumlah_box' => $request->jumlah_box,
                'jumlah_keping' => $request->jumlah_keping,
                'progres_koreksi' => $request->progres_koreksi,
                'jumlah_pending' => $request->jumlah_pending,
                'status' => $request->status
            ]);

            //Product created, return success response
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambah!',
                'data' => $koreksi
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
        $koreksi = $this->user->koreksi()->find($id);
    
        if (!$koreksi) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data not found.'
            ], 400);
        }
    
        return $koreksi;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function edit(koreksi $koreksi)
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
    public function update(Request $request, koreksi $koreksi)
    {
        //Validate data
        $data = $request->only('user_id', 'adding_id', 'gradding_id','mandor_id', 'kode_transaksi', 'kode_partai', 'no_register', 'tanggal_proses', 'jumlah_sbw', 'jumlah_box', 'jumlah_keping', 'progres_koreksi', 'jumlah_pending','status');
        $validator = Validator::make($data, [
        ]);

        

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $koreksi = $koreksi->update([
          'user_id' => $request->user_id,
            'adding_id' => $request->adding_id,
            'gradding_id' => $request->gradding_id,
            'mandor_id' => $request->mandor_id,
            'kode_partai' => $request->kode_partai,
            'kode_transaksi' => $request->kode_transaksi,
            'no_register' => $request->no_register,
            'tanggal_proses' => $request->tanggal_proses,
            'jumlah_sbw' => $request->jumlah_sbw,
            'jumlah_box' => $request->jumlah_box,
            'jumlah_keping' => $request->jumlah_keping,
            'progres_koreksi' => $request->progres_koreksi,
            'jumlah_pending' => $request->jumlah_pending,
            'status' => $request->status
        ]);

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'data berhasil diupdate',
            'data' => $koreksi
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function destroy(koreksi $koreksi)
    {
        $koreksi->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully'
        ], Response::HTTP_OK);
    }
}
