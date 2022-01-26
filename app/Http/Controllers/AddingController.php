<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\adding;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AddingController extends Controller
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

        $adding= $this->user->adding()->get();
         return response()->json([
          
            'success' => true,
            'message' => 'Load data',
            'data' => $adding
        ], Response::HTTP_OK);
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
        $data = $request->only('user_id', 'no_register', 'kode_partai','legal_source' ,'jenis_sbw_kotor', 'tanggal_panen', 'tanggal_penerima', 'alamat', 'no_kendaraan', 'jumlah_sbw_kotor', 'jumlah_pcs', 'warna', 'kondisi', 'status' , 'harga_kulak' );
        $validator = Validator::make($data, [
            'no_register' => 'required',
            'kode_partai' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new product
        $adding= $this->user->adding()->create([
            'user_id' => $request->user_id,
            'no_register' => $request->no_register,
            'kode_partai' => $request->kode_partai,
            'legal_source' => $request->legal_source,
            'jenis_sbw_kotor' => $request->jenis_sbw_kotor,
            'tanggal_panen' => $request->tanggal_panen,
            'tanggal_penerima' => $request->tanggal_penerima,
            'alamat' => $request->alamat,
            'no_kendaraan' => $request->no_kendaraan,
            'jumlah_sbw_kotor' => $request->jumlah_sbw_kotor,
            'jumlah_pcs' => $request->jumlah_pcs,
            'warna' => $request->warna,
            'kondisi' => $request->kondisi,
            'status' => $request->status,
            'harga_kulak' => $request->harga_kulak
        ]);

        //Product created, return success response
        return response()->json([
            'code' => 1,
            'success' => true,
            'message' => 'Data berhasil ditambah!',
            'data' => $adding
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\adding  $adding
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $adding = $this->user->adding()->find($id);
    
        if (!$adding) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product not found.'
            ], 400);
        }
    
        return $adding;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\adding  $adding
     * @return \Illuminate\Http\Response
     */
    public function edit(adding $adding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\adding  $adding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, adding $adding)
    {
        //Validate data
        $data = $request->only('user_id', 'no_register', 'kode_partai','legal_source', 'jenis_sbw_kotor', 'tanggal_panen', 'tanggal_penerima', 'alamat', 'no_kendaraan', 'jumlah_sbw_kotor', 'jumlah_pcs', 'warna', 'kondisi', 'status', 'harga_kulak');
        $validator = Validator::make($data, [
            
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $adding = $adding->update([
            'user_id' => $request->user_id,
            'no_register' => $request->no_register,
            'kode_partai' => $request->kode_partai,
            'legal_source' => $request->legal_source,
            'jenis_sbw_kotor' => $request->jenis_sbw_kotor,
            'tanggal_panen' => $request->tanggal_panen,
            'tanggal_penerima' => $request->tanggal_penerima,
            'alamat' => $request->alamat,
            'no_kendaraan' => $request->no_kendaraan,
            'jumlah_sbw_kotor' => $request->jumlah_sbw_kotor,
            'jumlah_pcs' => $request->jumlah_pcs,
            'warna' => $request->warna,
            'kondisi' => $request->kondisi,
            'status' => $request->status,
            'harga_kulak' => $request->harga_kulak,
        ]);

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'data adding updated successfully',
            'data' => $adding
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\adding  $adding
     * @return \Illuminate\Http\Response
     */
    public function destroy(adding $adding)
    {
        $adding->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully'
        ], Response::HTTP_OK);
    }

  
}
