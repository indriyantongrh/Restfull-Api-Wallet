<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\gradding;
use App\drykedua;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
class PengeringKeduaController extends Controller
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
            ->drykedua()
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
        $data = $request->only('user_id', 'adding_id','mandor_id', 'gradding_id','koreksi_id', 'dry_pertama_id', 'molding_id','kode_partai','no_register', 'tanggal_proses', 'jumlah_sbw','jumlah_keping', 'jumlah_box', 'kode_transaksi','jumlah_sbw_saldo','jumlah_box_saldo','jumlah_keping_saldo', 'status');
        $kode_transaksiExist = drykedua::where('kode_transaksi', '=', $request->input('kode_transaksi'))->first();
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
            $drykedua= $this->user->drykedua()->create([
                'user_id' => $request->user_id,
                'adding_id' => $request->adding_id,
                'gradding_id' => $request->gradding_id,
                'mandor_id' => $request->mandor_id,
                'koreksi_id' => $request->koreksi_id,
                'dry_pertama_id' => $request->dry_pertama_id,
                'molding_id' => $request->molding_id,
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
                'data' => $drykedua
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
        $drykedua = $this->user->drykedua()->find($id);
    
        if (!$drykedua) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data not found.'
            ], 400);
        }else{
            $gradding = gradding::where('id', $drykedua->gradding_id)->first();

            return response()->json([
                'id' => $drykedua->id,
                'adding_id' => $drykedua->adding_id,
                'gradding_id' => $drykedua->gradding_id,
                'mandor_id' => $drykedua->mandor_id,
                'koreksi_id' => $drykedua->koreksi_id,
                'dry_pertama_id' => $drykedua->dry_pertama_id,
                'molding_id' => $drykedua->molding_id,
                'kode_transaksi' => $drykedua->kode_transaksi,
                'kode_partai' => $drykedua->kode_partai,
                'no_register' => $drykedua->no_register,
                'tanggal_proses' => $drykedua->tanggal_proses,
                'jumlah_box' => $drykedua->jumlah_box,
                'jumlah_sbw' => $drykedua->jumlah_sbw,
                'jumlah_keping' => $drykedua->jumlah_keping,
                'user_id' => $drykedua->user_id,
                'status' => $drykedua->status,
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
    public function edit(drykedua $drykedua)
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
    public function update(Request $request, drykedua $drykedua)
    {
        //Validate data
        $data = $request->only('user_id', 'adding_id','mandor_id', 'gradding_id','koreksi_id', 'dry_pertama_id', 'molding_id','kode_partai','no_register', 'tanggal_proses', 'jumlah_sbw','jumlah_keping', 'jumlah_box', 'kode_transaksi', 'status');

        $validator = Validator::make($data, [
        ]);

        

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $drykedua = $drykedua->update([
           'user_id' => $request->user_id,
                'adding_id' => $request->adding_id,
                'gradding_id' => $request->gradding_id,
                'mandor_id' => $request->mandor_id,
                'koreksi_id' => $request->koreksi_id,
                'dry_pertama_id' => $request->dry_pertama_id,
                'molding_id' => $request->molding_id,
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
            'data' => $drykedua
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function destroy(drykedua $drykedua)
    {
        $drykedua->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully'
        ], Response::HTTP_OK);
    }
}
