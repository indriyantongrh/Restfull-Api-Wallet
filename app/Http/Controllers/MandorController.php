<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\mandor;
use App\gradding;
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
    public function index(Request $request)
    {
        $data =  $request->get('data');
          return $this->user->mandor()->where('progres_pekerja', 'like', "{$data}")->get();
        //  return $this->user->mandor()->get();
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
        $data = $request->only('user_id', 'adding_id', 'gradding_id','kode_partai', 'no_register','kode_transaksi','nip_pekerja' ,'tanggal_proses', 'jumlah_sbw','jumlah_box','jumlah_keping', 'nama_pekerja',  'progres_pekerja',  'status','kode_mandor'  );
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
            'jumlah_box' => $request->jumlah_box,
            'jumlah_keping' => $request->jumlah_keping,
            
            'nip_pekerja' => $request->nip_pekerja,
            'nama_pekerja' => $request->nama_pekerja,
            'progres_pekerja' => $request->progres_pekerja,
            'kode_mandor' => $request->kode_mandor,
            'status' => $request->status
        ]);

        $gradding = gradding::find($request->gradding_id);
        $gradding->jmlh_sbw_saldo = ($gradding->jmlh_sbw_saldo - $request->jumlah_sbw);
        $gradding->jmlh_keping_saldo = ($gradding->jmlh_keping_saldo - $request->jumlah_keping);
        $gradding->update();
       
        //Product created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambah!',
            'berhasil' => $gradding,
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
        }else{
            //return $mandor;
            $gradding = gradding::where('id', $mandor->gradding_id)->first();
            return response()->json([
                'id' => $mandor->id,
                'adding_id' => $mandor->adding_id,
                'gradding_id' => $mandor->gradding_id,
                'user_id' => $mandor->user_id,
                'kode_partai' => $mandor->kode_partai,
                'kode_transaksi' => $mandor->kode_transaksi,
                'kode_mandor' => $mandor->kode_mandor,
                'no_register' => $mandor->no_register,
                'jenis_grade' => $gradding->jenis_grade,
                'nip_pekerja' => $mandor->nip_pekerja,
                'nama_pekerja' => $mandor->nama_pekerja,
                'tanggal_proses' => $mandor->tanggal_proses,
                'jumlah_sbw' => $mandor->jumlah_sbw,
                'jumlah_box' => $mandor->jumlah_box,
                'jumlah_keping' => $mandor->jumlah_keping,
                'jumlah_sbw_selesai' => $mandor->jumlah_sbw_selesai,
                'jumlah_box_selesai' => $mandor->jumlah_box_selesai,
                'jumlah_keping_selesai' => $mandor->jumlah_keping_selesai,
                'progres_pekerja' => $mandor->progres_pekerja,
                'tanggal_selesai' => $mandor->tanggal_selesai,
                'status' => $mandor->status,
                'created_at' => $mandor->created_at,
                'updated_at' => $mandor->updated_at,
            ], 200);
        }

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
        $data = $request->only('user_id', 'adding_id', 'gradding_id','kode_partai', 'no_register','kode_transaksi', 'nip_pekerja','jumlah_sbw', 'jumlah_box', 'jumlah_keping','tanggal_proses', 'tanggal_selesai','jumlah_sbw_selesai', 'jumlah_box_selesai','jumlah_keping_selesai','nama_pekerja',  'progress_pekerja',  'status'  );
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
            'jumlah_box' => $request->jumlah_box,
            'jumlah_keping' => $request->jumlah_keping,
            'jumlah_sbw_selesai' => $request->jumlah_sbw_selesai,
            'jumlah_box_selesai' => $request->jumlah_box_selesai,
            'jumlah_keping_selesai' => $request->jumlah_keping_selesai,
            'nip_pekerja' => $request->nip_pekerja,
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
    public function destroy(mandor $mandor,  Request $request)
    {
            $mandorGet = $this->user->mandor()->find($mandor)->first();
            $jumlahsaldo = $mandorGet->jumlah_sbw;
            $kepingsaldo = $mandorGet->jumlah_keping;
            $idgrading = $mandorGet->gradding_id;

            $gradding = gradding::find($idgrading);
            $gradding->jmlh_sbw_saldo = ($gradding->jmlh_sbw_saldo + $jumlahsaldo);
            $gradding->jmlh_keping_saldo = ($gradding->jmlh_keping_saldo + $kepingsaldo);
            $gradding->update();

            $mandor->delete();

        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully',
            'datass' => $jumlahsaldo,
            'data' => $idgrading
        ], Response::HTTP_OK);

        // $data = $request->get('data');
        // $id = $request->get('id');
        // $getDatas =  mandor::where('kode_mandor', 'like', "{$data}")
        //                 ->first();
        // if ($getDatas == null ){
        //     // $data = $request->only('user_id', 'adding_id', 'gradding_id','kode_partai', 'no_register','kode_transaksi', 'tanggal_proses', 'jumlah_sbw','jumlah_box','jumlah_keping', 'nama_pekerja',  'progres_pekerja',  'status'   );
        //     $mandorGet = $this->user->mandor()->find($id)->first();
        //     $jumlahsaldo = $mandorGet->jumlah_sbw;
        //     $kepingsaldo = $mandorGet->jumlah_keping;
        //     $idgrading = $mandorGet->gradding_id;

        //     $gradding = gradding::find($idgrading);
        //     $gradding->jmlh_sbw_saldo = ($gradding->jmlh_sbw_saldo + $jumlahsaldo);
        //     $gradding->jmlh_keping_saldo = ($gradding->jmlh_keping_saldo + $kepingsaldo);
        //     $gradding->update();

        //     $mandor->delete();
        //     return response()->json([
        //                 'success' => true,
        //                 'pesancari' => 'data tidak ditemukan',
        //                 'message' => 'data berhasil di hapus'
        //             ], Response::HTTP_OK);
        // }else{
        //     return response()->json([
        //                 'success' => false,
        //                 'pesancari' => 'data  ditemukan',
        //                 'message' => 'data tidak dapat dihapus karena sudha di proses'

        //             ], Response::HTTP_OK);

        // }
    }

       public function destroyend(mandor $mandor,  Request $request)
    {
        

        $data = $request->get('data');
        $id = $request->get('id');
        $getDatas =  mandor::where('kode_transaksi', 'like', "{$data}")
                        ->first();
        if ($getDatas == null ){
            // $data = $request->only('user_id', 'adding_id', 'gradding_id','kode_partai', 'no_register','kode_transaksi', 'tanggal_proses', 'jumlah_sbw','jumlah_box','jumlah_keping', 'nama_pekerja',  'progres_pekerja',  'status'   );
            $mandorGet = $this->user->mandor()->find($id)->first();
            $jumlahsaldo = $mandorGet->jumlah_sbw;
            $kepingsaldo = $mandorGet->jumlah_keping;
            $idgrading = $mandorGet->gradding_id;

            $gradding = gradding::find($idgrading);
            $gradding->jmlh_sbw_saldo = ($gradding->jmlh_sbw_saldo + $jumlahsaldo);
            $gradding->jmlh_keping_saldo = ($gradding->jmlh_keping_saldo + $kepingsaldo);
            $gradding->update();

            $mandor->delete();
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
