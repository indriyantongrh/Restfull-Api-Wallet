<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\absensi;
use App\datapekerja;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class AbsensiController extends Controller
{
    //create load data
    public function index()
    {
        // return absensi::where('isDelete', '0')->orderBy('id', 'DESC')->get();
        return  DB::table('absensi')
                    ->where('absensi.isDelete', 'like', "0")
                    ->leftjoin('datapekerja','datapekerja.nik' , 'like',  'absensi.nik')
                    ->select('absensi.*', 
                            'datapekerja.nama_pekerja as nama_pekerja')
                    ->orderBy('absensi.id', 'DESC')->get();
    }

    public function store(Request $request)
     {

          $data= $request->get('data');
         $response =  absensi::insert(json_decode($data, true)); 
         //Product created, return success response
         return response()->json([
             'code' => 1,
             'success' => true,
             'message' => 'Data berhasil ditambah!',
             'data' => $data
         ], Response::HTTP_OK);
     }

    public function show($id)
    {
        $data = absensi()->find($id);
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data not found.'
            ], 400);
        }
        return $data;
    }

    public function destroy($id)
    {
        absensi::where('id', $id)->update(['isDelete' => '1']);
        
        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully',
        ], Response::HTTP_OK);
    }

    public function search(Request $request)
    {
        $data= $request->get('data');
        $response =  datapekerja::where('nik', 'like', "{$data}")->where('isDelete', 'like', "0")->first();
         //Product created, return success response
        return response()->json([
             'code' => 1,
             'success' => true,
             'message' => 'Data ditemuka!',
             'data' => $response
         ], Response::HTTP_OK);
}
}
