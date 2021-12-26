<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\mandor;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class MandorController extends Controller
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
            ->mandor()
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
        $data = $request->
                    only('id', 
                    'user_id', 
                    'adding_id', 
                    '\gradding_id',
                    'kode_partai', 
                    'no_register',
                    'kode_transaksi', 
                    'tanggal_proses', 
                    'tanggal_selesai', 
                    'jumlah_sbw', 
                    'nama-pekerja', 
                    'progress_pekerja', 
                    'status',  );
        $validator = Validator::make($data, [
            // 'no_register' => 'required',
            // 'kode_partai' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new product
        $mandor= $this->user->mandor()->create([
            'user_id' => $request->user_id,
            'adding_id' => $request->adding_id,
            'kode_partai' => $request->kode_partai,
            'no_register' => $request->no_register,
            'tanggal_proses' => $request->tanggal_proses,
            'jumlah_sbw' => $request->jumlah_sbw,
            'jumlah_keping' => $request->jumlah_keping,
            'jumlah_box' => $request->jumlah_box,
            'jenis_grade' => $request->jenis_grade,
            'kode_transaksi' => $request->kode_transaksi,
            'status' => $request->status
            
        ]);

        //Product created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambah!',
            'data' => $mandor
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
        $gradding = $this->user->gradding()->find($id);
    
        if (!$gradding) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data not found.'
            ], 400);
        }
    
        return $gradding;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function edit(gradding $gradding)
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
    public function update(Request $request, gradding $gradding)
    {
        //Validate data
        $data = $request->only('user_id', 'adding_id', 'kode_partai', 'no_register','tanggal_proses' ,'jumlah_sbw', 'jumlah_keping', 'jumlah_box', 'jenis_grade', 'kode_transaksi', 'status' );

       // $data = $request->only('user_id', 'adding_id', 'kode_partai','tanggal_proses' ,'jumlah-sbw', 'jenis_grade', 'kode_transaksi', 'status');
        $validator = Validator::make($data, [
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $gradding = $gradding->update([
           'user_id' => $request->user_id,
            'adding_id' => $request->adding_id,
            'kode_partai' => $request->kode_partai,
            'no_register' => $request->no_register,
            'tanggal_proses' => $request->tanggal_proses,
            'jumlah_sbw' => $request->jumlah_sbw,
            'jumlah_keping' => $request->jumlah_keping,
            'jumlah_box' => $request->jumlah_box,
            'jenis_grade' => $request->jenis_grade,
            'kode_transaksi' => $request->kode_transaksi,
            'status' => $request->status
        ]);

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'data adding updated successfully',
            'data' => $gradding
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function destroy(gradding $gradding)
    {
        $gradding->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully'
        ], Response::HTTP_OK);
    }
}
