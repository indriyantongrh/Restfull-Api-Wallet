<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use App\adding;
use App\gradding;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function register(Request $request)
    {
    	//Validate data
        $data = $request->only('name', 'email', 'password', 'role_id', 'role_name');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
            'role_id' => 'required',
            'role_name' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
        	'name' => $request->name,
        	'email' => $request->email,
        	'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'role_name' => $request->role_name
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function login(Request $request) {
         $credentials = [
            'email' => $request->email,
            'password' => $request->password,
         ];

         if(auth()->attempt($credentials)){
             $token = JWTAuth::attempt($credentials);
             $user = User::where('email', $request->email)->first();

             if($user){
                return response()->json(
                    [
                        'code' => 1,
                        'success' => true,
                        'message' => 'yeay ! you are logged in '.$user->name,
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'roleId' => $user->role_id,
                        'roleName' => $user->role_name,
                        'token' => $token,
                    ], 200);
             }else{
                return response()->json(
                    [
                        'code' => -2,
                        'success' => false,
                        'message' => 'User is not found'
                    ], 404);
             }
         }else{
             return response()->json(
                [
                    'code' => -2,
                    'success' => false,
                    'message' => 'User is not found'
                ], 401);
         }
    }
 
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
          
                'error' => $validator->messages()], 200);
        }

        //Request is validated
        //Creat token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
 	
 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => "admin"
        ]);
    }
 
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

		//Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

      public function searchpartai(Request $request)
    {
        //$adding = $this->adding()->get();
        $data = $request->get('data');
        $kodepartai = adding::where('kode_partai', 'like', "{$data}")
                    ->get();


            return response()->json([   
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $kodepartai
            ], Response::HTTP_OK);
    }

    public function searchtransaksi(Request $request)
    {
        //$adding = $this->adding()->get();
        $data = $request->get('data');
        $kodetransaksi = gradding::where('kode_transaksi', 'like', "{$data}")
                    ->get();


            return response()->json([   
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $kodetransaksi
            ], Response::HTTP_OK);
    }
 
    public function get_user()
    {
        // $this->validate($request, [
        //     'token' => 'required'
        // ]);
 
        // $user = JWTAuth::authenticate($request->token);
 
        // return response()->json(['user' => $user]);
         return response()->json(['user' => auth()->user()], 200);
    }
}
