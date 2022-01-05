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
        $data = $request->only('user_id', 'adding_id', 'gradding_id','kode_partai', 'no_register','kode_transaksi', 'tanggal_proses', 'jumlah_sbw', 'nama_pekerja',  'progres_pekerja',  'status'  );
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
            'gradding_id' => $request->gradding_id,
            'kode_partai' => $request->kode_partai,
            'kode_transaksi' => $request->kode_transaksi,
            'no_register' => $request->no_register,
            'tanggal_proses' => $request->tanggal_proses,
            'jumlah_sbw' => $request->jumlah_sbw,
            'nama_pekerja' => $request->nama_pekerja,
            'progres_pekerja' => $request->progres_pekerja,
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
        $mandor = $this->user->mandor()->find($id);
    
        if (!$mandor) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data not found.'
            ], 400);
        }
    
        return $mandor;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function edit(mandor $mandor)
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
    public function update(Request $request, mandor $mandor)
    {
        //Validate data
        $data = $request->only('user_id', 'adding_id', 'gradding_id','kode_partai', 'no_register','kode_transaksi', 'jumlah_sbw_selesai','tanggal_proses', 'tanggal_selesai', 'jumlah_sbw_sebelum','jumlah_sbw_sesudah', 'nama_pekerja',  'progress_pekerja',  'status'  );
        $validator = Validator::make($data, [
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $mandor = $mandor->update([
           'user_id' => $request->user_id,
            'adding_id' => $request->adding_id,
            'gradding_id' => $request->gradding_id,
            'kode_partai' => $request->kode_partai,
            'kode_transaksi' => $request->kode_transaksi,
            'no_register' => $request->no_register,
            'tanggal_proses' => $request->tanggal_proses,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jumlah_sbw' => $request->jumlah_sbw,
            'jumlah_sbw_selesai' => $request->jumlah_sbw_selesai,
            'nama_pekerja' => $request->nama_pekerja,
            'progres_pekerja' => $request->progres_pekerja,
            'status' => $request->status
        ]);

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'data adding updated successfully',
            'data' => $mandor
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function destroy(mandor $mandor)
    {
        $mandor->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully'
        ], Response::HTTP_OK);
    }
}
