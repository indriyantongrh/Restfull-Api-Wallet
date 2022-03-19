<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\lookup;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class LookupController extends Controller
{
        // function add user atau register
    public function store(Request $request)
    {
    	//Validate data
        $data = $request->only('type', 'name' );

        //Request is valid, create new user
        $lookup = lookup::create([
        	'type' => $request->type,
        	'name' => $request->name,
        	
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $lookup
        ], Response::HTTP_OK);
    }

    // function show user by id
    public function show($id)
    {
        $lookup = lookup::find($id);
    
        if (!$lookup) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data not found.'
            ], 400);
        }
    
        return $lookup;
    }

    //  function get all user
    public function index()
        {
            return lookup::orderBy('id', 'DESC')->get();
        }

    //  function update user
        public function update(Request $request,  $id)
            {
                //Validate data
                $data = $request->only('type', 'name' );
                $validator = Validator::make($data, [
                ]);

                //Send failed response if request is not valid
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->messages()], 200);
                }

                //Request is valid, update product
                $lookup = lookup::where('id', $id)->update([
                    'name' => $request->name,
                    'type' => $request->type,
                    
            ]);
                //Product updated, return success response
                return response()->json([
                    'success' => true,
                    'message' => 'data berhasil diupdata!',
                    'data' => $lookup
                ], Response::HTTP_OK);
            }


        //  function delete user
    public function destroy($id)
    {
        lookup::where('id', $id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully'
        ], Response::HTTP_OK);
    }

     public function searchlookup(Request $request)
        {
            //$adding = $this->adding()->get();
            $type = $request->get('type');
            $data = lookup::where('type', 'like', "{$type}")->get();

            if($data){
                 return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' => $data
                    
                ], 200);

            }else{
                 return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }

        }
}