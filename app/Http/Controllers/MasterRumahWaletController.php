<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\rumahwalet;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class MasterRumahWaletController extends Controller
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
            ->rumahwalet()
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
        $data = $request->only('user_id','nama', 'alamat' , );
        $validator = Validator::make($data, [
            // 'no_register' => 'required',
            // 'kode_partai' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new product
        $rumahwalet = $this->user->rumahwalet()->create([
            'nama' => $request->nama,
            'alamat' => $request->alamat
            
        ]);

        //Product created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil ditambah!',
            'data' => $rumahwalet
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
        $rumahwalet = $this->user->rumahwalet()->find($id);
    
        if (!$rumahwalet) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data not found.'
            ], 400);
        }
    
        return $rumahwalet;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function edit(rumahwalet $rumahwalet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\datapekerja  $datapekerja
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, rumahwalet $rumahwalet)
    {
        //Validate data
        $data = $request->only('nama', 'alamat' );
        $validator = Validator::make($data, [
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $rumahwalet = $rumahwalet->update([
        
           'nama' => $request->nama,
            'alamat' => $request->alamat
        ]);

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'data Mandor updated successfully',
            'data' => $rumahwalet
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\gradding  $gradding
     * @return \Illuminate\Http\Response
     */
    public function destroy(rumahwalet $rumahwalet)
    {
        $rumahwalet->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully'
        ], Response::HTTP_OK);
    }
}