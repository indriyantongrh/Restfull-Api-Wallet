<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use App\adding;
use App\gradding;
use App\datapekerja;
use App\roles;
use App\mandor;
use App\koreksi;
use App\rumahwalet;
use App\drypertama;
use App\drykedua;
use App\molding;
use App\gradingakhir;
use App\pemanas;
use App\packing;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    // function add user atau register
    public function register(Request $request)
    {
    	//Validate data
        $data = $request->only('name', 'email', 'password', 'role_id', 'role_name');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required',
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

    // function show user by id
    public function show($id)
    {
        $users = User::find($id);
    
        if (!$users) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data not found.'
            ], 400);
        }
    
        return $users;
    }
    
    // function get all role
    public function indexrole()
        {
            return roles::all();
        }

    //  function get all user
    public function index()
        {
            return User::all();
        }

    //  function update user
        public function update(Request $request,  $user)
            {
                //Validate data
                $data = $request->only('name', 'email','role_id', 'role_name'  );
                $validator = Validator::make($data, [
                ]);

                //Send failed response if request is not valid
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->messages()], 200);
                }

                //Request is valid, update product
                $users = User::where('id', $user)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role_id' => $request->role_id,
                    'role_name' => $request->role_name,
            ]);
                //Product updated, return success response
                return response()->json([
                    'success' => true,
                    'message' => 'data berhasil diupdata!',
                    'data' => $users
                ], Response::HTTP_OK);
            }

            // update password
             public function updatepassword(Request $request,  $user)
            {
                //Validate data
                $data = $request->only('password'  );
                $validator = Validator::make($data, [
                ]);

                //Send failed response if request is not valid
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->messages()], 200);
                }

                //Request is valid, update product
                $users = User::where('id', $user)->update([
                    'password' => bcrypt($request->password),
               
            ]);
                //Product updated, return success response
                return response()->json([
                    'success' => true,
                    'message' => 'password berhasil diperbaharui!',
                    'data' => $users
                ], Response::HTTP_OK);
            }


        //  function delete user
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'data  deleted successfully'
        ], Response::HTTP_OK);
    }


    // Fungsi login 

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

    public function searchmandorselesai(Request $request)
        {
            //$adding = $this->adding()->get();
            $data = $request->get('data');
            $kodetransaksi = mandor::where('kode_transaksi', 'like', "{$data}")
                        ->first();

            if($kodetransaksi){
                 $gradding = gradding::where('id', $kodetransaksi->gradding_id)->first();

                 return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' =>
                    [
                        'id' => $kodetransaksi->id,
                        'user_id' => $kodetransaksi->user_id,
                        'gradding_id' => $kodetransaksi->gradding_id,
                        'adding_id' => $kodetransaksi->adding_id,
                        'kode_transaksi' => $kodetransaksi->kode_transaksi,
                        'kode_partai' => $kodetransaksi->kode_partai,
                        'no_register' => $kodetransaksi->no_register,
                        'jenis_grade' => $gradding->jenis_grade,

                    ]
                ], 200);

            }else{
                 return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 404);
            }

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

        public function searchtransaksiproses(Request $request)
        {
            //$adding = $this->adding()->get();
            $data = $request->get('data');
            $kodetransaksi = mandor::where('kode_transaksi', 'like', "{$data}")
                        ->first();

            if($kodetransaksi){
                $gradding = gradding::where('id', $kodetransaksi->gradding_id)->first();

                 return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' =>
                    [
                        'id' => $kodetransaksi->id,
                        'user_id' => $kodetransaksi->user_id,
                        'gradding_id' => $kodetransaksi->gradding_id,
                        'adding_id' => $kodetransaksi->adding_id,
                        'kode_transaksi' => $kodetransaksi->kode_transaksi,
                        'kode_partai' => $kodetransaksi->kode_partai,
                        'no_register' => $kodetransaksi->no_register,
                        'jenis_grade' => $gradding->jenis_grade,

                    ]
                ], 200);

            }else{
                 return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 200);
            }
        }

         public function searchkodemandor(Request $request)
        {
            //$adding = $this->adding()->get();
            $data = $request->get('data');
            $kodemandor = mandor::where('kode_mandor', 'like', "{$data}")
                        ->first();

            if($kodemandor){
                $gradding = gradding::where('id', $kodemandor->gradding_id)->first();

                 return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' =>
                    [
                        'id' => $kodemandor->id,
                        'user_id' => $kodemandor->user_id,
                        'gradding_id' => $kodemandor->gradding_id,
                        'adding_id' => $kodemandor->adding_id,
                        'kode_transaksi' => $kodemandor->kode_transaksi,
                        'kode_partai' => $kodemandor->kode_partai,
                        'no_register' => $kodemandor->no_register,
                        'tanggal_proses' => $kodemandor->tanggal_proses,
                        'jumlah_sbw' => $kodemandor->jumlah_sbw,
                        'jumlah_box' => $kodemandor->jumlah_box,
                        'jumlah_keping' => $kodemandor->jumlah_keping,
                        'nama_pekerja' => $kodemandor->nama_pekerja,
                        'progres_pekerja' => $kodemandor->progres_pekerja,
                        'kode_mandor' => $kodemandor->kode_mandor,
                        'jenis_grade' => $gradding->jenis_grade,

                    ]
                ], 200);

            }else{
                 return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 200);
            }
        }

        public function searchtransaksikoreksi(Request $request)
        {
            //$adding = $this->adding()->get();
            $data = $request->get('data');
            $kodetransaksi = koreksi::where('kode_transaksi', 'like', "{$data}")
                        ->first();


            if($kodetransaksi){
                $gradding = gradding::where('id', $kodetransaksi->gradding_id)->first();

                 return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' =>
                    [
                        'id' => $kodetransaksi->id,
                        'user_id' => $kodetransaksi->user_id,
                        'gradding_id' => $kodetransaksi->gradding_id,
                        'adding_id' => $kodetransaksi->adding_id,
                        'mandor_id' => $kodetransaksi->mandor_id,
                        'kode_transaksi' => $kodetransaksi->kode_transaksi,
                        'kode_partai' => $kodetransaksi->kode_partai,
                        'no_register' => $kodetransaksi->no_register,
                        'tanggal_proses' => $kodetransaksi->tanggal_proses,
                        'jumlah_sbw' => $kodetransaksi->jumlah_sbw,
                        'jumlah_box' => $kodetransaksi->jumlah_box,
                        'jumlah_keping' => $kodetransaksi->jumlah_keping,
                        'progres_koreksi' => $kodetransaksi->progres_koreksi,
                        'jumlah_pending' => $kodetransaksi->jumlah_pending,
                        'jenis_grade' => $gradding->jenis_grade,

                    ]
                ], 200);

            }else{
                 return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 200);
            }
        }


        public function searchtransaksidrypertama(Request $request)
        {
            //$adding = $this->adding()->get();
            $data = $request->get('data');
            $kodetransaksi = drypertama::where('kode_transaksi', 'like', "{$data}")
                        ->first();


            if($kodetransaksi){
                $gradding = gradding::where('id', $kodetransaksi->gradding_id)->first();

                 return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' =>
                    [
                        'id' => $kodetransaksi->id,
                        'user_id' => $kodetransaksi->user_id,
                        'gradding_id' => $kodetransaksi->gradding_id,
                        'adding_id' => $kodetransaksi->adding_id,
                        'mandor_id' => $kodetransaksi->mandor_id,
                        'koreksi_id' => $kodetransaksi->koreksi_id,
                        
                        'kode_transaksi' => $kodetransaksi->kode_transaksi,
                        'kode_partai' => $kodetransaksi->kode_partai,
                        'no_register' => $kodetransaksi->no_register,
                        'jenis_grade' => $gradding->jenis_grade,

                    ]
                ], 200);

            }else{
                 return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 200);
            }
        }

        public function searchtransaksimolding(Request $request)
        {
            //$adding = $this->adding()->get();
            $data = $request->get('data');
            $kodetransaksi = molding::where('kode_transaksi', 'like', "{$data}")
                        ->first();
            if($kodetransaksi){
                $gradding = gradding::where('id', $kodetransaksi->gradding_id)->first();

                 return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' =>
                    [
                        'id' => $kodetransaksi->id,
                        'user_id' => $kodetransaksi->user_id,
                        'gradding_id' => $kodetransaksi->gradding_id,
                        'adding_id' => $kodetransaksi->adding_id,
                        'mandor_id' => $kodetransaksi->mandor_id,
                        'koreksi_id' => $kodetransaksi->koreksi_id,
                        'dry_pertama_id' => $kodetransaksi->dry_pertama_id,
                        'kode_transaksi' => $kodetransaksi->kode_transaksi,
                        'kode_partai' => $kodetransaksi->kode_partai,
                        'no_register' => $kodetransaksi->no_register,
                        'jenis_grade' => $gradding->jenis_grade,

                    ]
                ], 200);

            }else{
                 return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 200);
            }
        }

        public function searchtransaksidrykedua(Request $request)
        {
            //$adding = $this->adding()->get();
            $data = $request->get('data');
            $kodetransaksi = drykedua::where('kode_transaksi', 'like', "{$data}")
                        ->first();
            if($kodetransaksi){
                $gradding = gradding::where('id', $kodetransaksi->gradding_id)->first();

                 return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' =>
                    [
                        'id' => $kodetransaksi->id,
                        'user_id' => $kodetransaksi->user_id,
                        'gradding_id' => $kodetransaksi->gradding_id,
                        'adding_id' => $kodetransaksi->adding_id,
                        'mandor_id' => $kodetransaksi->mandor_id,
                        'koreksi_id' => $kodetransaksi->koreksi_id,
                        'dry_pertama_id' => $kodetransaksi->dry_pertama_id,
                        'molding_id' => $kodetransaksi->molding_id,
                        'kode_transaksi' => $kodetransaksi->kode_transaksi,
                        'kode_partai' => $kodetransaksi->kode_partai,
                        'jumlah_sbw_saldo' => $kodetransaksi->jumlah_sbw_saldo,
                        'jumlah_box_saldo' => $kodetransaksi->jumlah_box_saldo,
                        'jumlah_keping_saldo' => $kodetransaksi->jumlah_keping_saldo,
                        'no_register' => $kodetransaksi->no_register,
                        'jenis_grade' => $gradding->jenis_grade,

                    ]
                ], 200);

            }else{
                 return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 200);
            }
        }

        public function searchtransaksigradeakhir(Request $request)
        {
            //$adding = $this->adding()->get();
            $data = $request->get('data');
            $kodetransaksi = gradingakhir::where('kode_transaksi_grading', 'like', "{$data}")
                        ->first();
            if($kodetransaksi){
                // $gradding = gradding::where('id', $drykedua->gradding_id)->first();
                $drykeduas = drykedua::where('id', $kodetransaksi->id_dry_kedua)->first();

                 return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' =>
                    [
                        'id' => $kodetransaksi->id,
                        'user_id' => $kodetransaksi->user_id,
                        'gradding_id' => $drykeduas->gradding_id,
                        'adding_id' => $drykeduas->adding_id,
                        'mandor_id' => $drykeduas->mandor_id,
                        'koreksi_id' => $drykeduas->koreksi_id,
                        'dry_pertama_id' => $drykeduas->dry_pertama_id,
                        'molding_id' => $drykeduas->molding_id,
                        'id_dry_kedua' => $kodetransaksi->id_dry_kedua,
                        'kode_transaksi' => $kodetransaksi->kode_transaksi,
                        'kode_partai' => $kodetransaksi->kode_partai,
                        'jumlah_sbw_grading' => $kodetransaksi->jumlah_sbw_grading,
                        'jumlah_keping_saldo' => $kodetransaksi->jumlah_keping_saldo,
                        'kode_register' => $kodetransaksi->kode_register,
                        'created_at' => $kodetransaksi->created_at,

                    ]
                ], 200);

            }else{
                 return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ], 200);
            }
        }

        public function searchGradingAkhir(Request $request)
        {
            //$adding = $this->adding()->get();
            $data = $request->get('data');
            $kodetransaksi = gradingakhir::where('kode_transaksi_grading', 'like', "{$data}")
                        ->get();
            if(!$kodetransaksi){
                // $gradding = gradding::where('id', $drykedua->gradding_id)->first();
                // $drykeduas = drykedua::where('id', $kodetransaksi->id_dry_kedua)->first();
                return response()->json([
                    'success' => false,
                    'message' => 'Data Kosong',
                ], 200);
              

            }else{
                   return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' => $kodetransaksi
                ], 200);
                
            }
        }

        public function searchKodeGAStreaming(Request $request)
        {
            //$adding = $this->adding()->get();
            $data = $request->get('data');
            $kodetransaksi = pemanas::where('kode_transaksi_grading', 'like', "{$data}")
                        ->get();
            if(!$kodetransaksi){
                // $gradding = gradding::where('id', $drykedua->gradding_id)->first();
                // $drykeduas = drykedua::where('id', $kodetransaksi->id_dry_kedua)->first();
                return response()->json([
                    'success' => false,
                    'message' => 'Data Kosong',
                ], 200);
              

            }else{
                   return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' => $kodetransaksi
                ], 200);
                
            }
        }

        public function searchKodeGAPacking(Request $request)
        {
            //$adding = $this->adding()->get();
            $data = $request->get('data');
            $kodetransaksi = packing::where('kode_transaksi_grading', 'like', "{$data}")
                        ->get();
            if(!$kodetransaksi){
                // $gradding = gradding::where('id', $drykedua->gradding_id)->first();
                // $drykeduas = drykedua::where('id', $kodetransaksi->id_dry_kedua)->first();
                return response()->json([
                    'success' => false,
                    'message' => 'Data Kosong',
                ], 200);
              

            }else{
                   return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'data' => $kodetransaksi
                ], 200);
                
            }
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

    public function listNama(Request $request)
        {
        $listnama = datapekerja::all();
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $listnama
            ], Response::HTTP_OK);
    }

     public function allAdding(Request $request)
        {
        $data = adding::first()->orderBy('id', 'DESC')->get();
        $sbwsum = adding::sum('jumlah_sbw_kotor');
        $pcssum = adding::sum('jumlah_pcs');
        $boxsum = adding::sum('jumlah_box');
        $number = 0;
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' =>  $data,
            ], Response::HTTP_OK);
    }

    public function allGradding(Request $request)
        {
        $data = gradding::orderBy('id', 'DESC')->get();
        $sbwsum = gradding::sum('jumlah_sbw');
        $pcssum = gradding::sum('jumlah_keping');
        $boxsum = gradding::sum('jumlah_box');
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $data
            ], Response::HTTP_OK);
    }

    public function allMandor(Request $request)
        {
        $data = mandor::orderBy('id', 'DESC')->get();
        $sbwsum = mandor::sum('jumlah_sbw');
        $pcssum = mandor::sum('jumlah_keping');
        $boxsum = mandor::sum('jumlah_box');
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                 'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $data
            ], Response::HTTP_OK);
    }
      public function allKoreksi(Request $request)
        {
        $data = koreksi::orderBy('id', 'DESC')->get();
        $sbwsum = koreksi::sum('jumlah_sbw');
        $pcssum = koreksi::sum('jumlah_keping');
        $boxsum = koreksi::sum('jumlah_box');
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $data
            ], Response::HTTP_OK);
    }
      public function allDrypertama(Request $request)
        {
        $data = drypertama::orderBy('id', 'DESC')->get();
        $sbwsum = drypertama::sum('jumlah_sbw');
        $pcssum = drypertama::sum('jumlah_keping');
        $boxsum = drypertama::sum('jumlah_box');
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $data
            ], Response::HTTP_OK);
    }

      public function allMolding(Request $request)
        {
        $data = molding::orderBy('id', 'DESC')->get();
        $sbwsum = molding::sum('jumlah_sbw');
        $pcssum = molding::sum('jumlah_keping');
        $boxsum = molding::sum('jumlah_box');
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $data
            ], Response::HTTP_OK);
    }

      public function allDrykedua(Request $request)
        {
        $data = drykedua::orderBy('id', 'DESC')->get();
        $sbwsum = drykedua::sum('jumlah_sbw');
        $pcssum = drykedua::sum('jumlah_keping');
        $boxsum = drykedua::sum('jumlah_box');
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $data
            ], Response::HTTP_OK);
    }

    public function allGradAkhir(Request $request)
        {
        $data = gradingakhir::orderBy('id', 'DESC')->get();
        $sbwsum = gradingakhir::sum('jumlah_sbw_grading');
        $pcssum = gradingakhir::sum('jumlah_pcs');
       
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'data' => $data
            ], Response::HTTP_OK);
    }
     public function allKartuStock(Request $request)
        {

        $data = DB::table('transaksi_data_grading_akhir')
                        ->leftjoin('streaming','streaming.kode_transaksi_grading' , '=',  'transaksi_data_grading_akhir.kode_transaksi_grading')
                        ->select('transaksi_data_grading_akhir.*',
                            'streaming.status_jual as status_jual')
                        ->orderBy('id', 'DESC')
                        ->get();
        $sbwsum = DB::table('transaksi_data_grading_akhir')
                        ->leftjoin('streaming','streaming.kode_transaksi_grading' , '=',  'transaksi_data_grading_akhir.kode_transaksi_grading')
                        ->orderBy('id', 'DESC')
                        ->sum('transaksi_data_grading_akhir.jumlah_sbw_grading');
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'data' => $data
            ], Response::HTTP_OK);
    }
     public function allStreaming(Request $request)
        {
        $data = pemanas::orderBy('id', 'DESC')->get();
        // $sbwsum = pemanas::sum('jumlah_sbw_grading');
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                // 'sbwTotal' => $sbwsum,
                'data' => $data
            ], Response::HTTP_OK);
    }

    public function allPacking(Request $request)
        {
        $data = packing::orderBy('id', 'DESC')->get();
        // $sbwsum = pemanas::sum('jumlah_sbw_grading');
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                // 'sbwTotal' => $sbwsum,
                'data' => $data
            ], Response::HTTP_OK);
    }

     public function allRumahWalet(Request $request)
        {
        $data = rumahwalet::all();
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $data
            ], Response::HTTP_OK);
    }

    public function filterbyDateAdding(Request $request){
        $from = $request->from;
        $to = $request->to;
        $filterDate = adding::whereBetween('tanggal_penerima', [$from , $to])->get();
        $sbwsum = adding::whereBetween('tanggal_penerima', [$from , $to])->sum('jumlah_sbw_kotor');
        $pcssum = adding::whereBetween('tanggal_penerima', [$from , $to])->sum('jumlah_pcs');
        $boxsum = adding::whereBetween('tanggal_penerima', [$from , $to])->sum('jumlah_box');
        if ($filterDate){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filterDate
            ],  200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
         
    }

    public function filterbyDateGradding(Request $request){
        $from = $request->from;
        $to = $request->to;
        $filterDate = gradding::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->get();;
        $sbwsum = gradding::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = gradding::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = gradding::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_box');
        if ($filterDate){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filterDate
            ],  200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterbyDateMandor(Request $request){
        $from = $request->from;
        $to = $request->to;
        $filterDate = mandor::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->get();
        $sbwsum = mandor::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = mandor::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = mandor::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filterDate){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filterDate
            ],  200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

     public function filterbyDateKoreksi(Request $request){
        $from = $request->from;
        $to = $request->to;
        $filterDate = koreksi::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->get();
         $sbwsum = koreksi::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = koreksi::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = koreksi::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filterDate){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filterDate
            ],  200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

     public function filterbyDateDrypertama(Request $request){
        $from = $request->from;
        $to = $request->to;
        $filterDate = drypertama::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->get();
        $sbwsum = drypertama::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = drypertama::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = drypertama::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filterDate){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filterDate
            ],  200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

     public function filterbyDateMolding(Request $request){
        $from = $request->from;
        $to = $request->to;
        
        $filterDate = molding::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->get();
        $sbwsum = molding::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = molding::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = molding::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filterDate){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                 'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filterDate
            ],  200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

     public function filterbyDateDrykedua(Request $request){
        $from = $request->from;
        $to = $request->to;
        $filterDate = drykedua::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->get();
        $sbwsum = drykedua::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = drykedua::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = drykedua::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_box');
        if ($filterDate){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filterDate
            ],  200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterbyDateGradingakhir(Request $request){
        $from = $request->from;
        $to = $request->to;
        $filterDate = gradingakhir::whereBetween('created_at', [$from , $to])->orderBy('id', 'DESC')->get();
        $sbwsum = gradingakhir::whereBetween('created_at', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_sbw_grading');
        $pcssum = gradingakhir::whereBetween('created_at', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_pcs');
        // $boxsum = drykedua::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_box');
        if ($filterDate){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                // 'boxTotal' => $boxsum,
                'data' => $filterDate
            ],  200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterbyDateStreaming(Request $request){
        $from = $request->from;
        $to = $request->to;
        $filterDate = pemanas::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->get();
        // $sbwsum = gradingakhir::whereBetween('created_at', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_sbw_grading');
        // $pcssum = drykedua::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_keping');
        // $boxsum = drykedua::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_box');
        if ($filterDate){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                // 'sbwTotal' => $sbwsum,
                // 'pcsTotal' => $pcssum,
                // 'boxTotal' => $boxsum,
                'data' => $filterDate
            ],  200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterbyDatePacking(Request $request){
        $from = $request->from;
        $to = $request->to;
        $filterDate = packing::whereBetween('tanggal_packing', [$from , $to])->orderBy('id', 'DESC')->get();
        // $sbwsum = gradingakhir::whereBetween('created_at', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_sbw_grading');
        // $pcssum = drykedua::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_keping');
        // $boxsum = drykedua::whereBetween('tanggal_proses', [$from , $to])->orderBy('id', 'DESC')->sum('jumlah_box');
        if ($filterDate){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                // 'sbwTotal' => $sbwsum,
                // 'pcsTotal' => $pcssum,
                // 'boxTotal' => $boxsum,
                'data' => $filterDate
            ],  200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterbyDateKartuStock(Request $request){
        $from = $request->from;
        $to = $request->to;
        $filterDate = DB::table('transaksi_data_grading_akhir')
                        ->whereBetween('transaksi_data_grading_akhir.created_at', [$from , $to])
                        ->leftjoin('streaming','streaming.kode_transaksi_grading' , '=',  'transaksi_data_grading_akhir.kode_transaksi_grading')
                        ->select('transaksi_data_grading_akhir.*',
                            'streaming.status_jual as status_jual')
                        ->orderBy('id', 'DESC')
                        ->get();
        $sbwsum = DB::table('transaksi_data_grading_akhir')
                        ->whereBetween('transaksi_data_grading_akhir.created_at', [$from , $to])
                        ->leftjoin('streaming','streaming.kode_transaksi_grading' , '=',  'transaksi_data_grading_akhir.kode_transaksi_grading')
                        ->orderBy('id', 'DESC')
                        ->sum('transaksi_data_grading_akhir.jumlah_sbw_grading');
        if ($filterDate){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'data' => $filterDate
            ],  200);
            
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function showadding($id)
    {
        $adding = adding::whereId($id)->first();
        if (!$adding) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data tidak ditemukan.'
            ], 400);
        }
        return $adding;
    }

    public function showgradding($id)
    {
        $gradding = gradding::whereId($id)->first();
        if (!$gradding) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data tidak ditemukan.'
            ], 400);
        }
        return $gradding;
    }

    public function showmandor($id)
    {
        $mandor = mandor::whereId($id)->first();
        if (!$mandor) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data tidak ditemukan.'
            ], 400);
        }
        return $mandor;
    }

       public function showkoreksi($id)
    {
        $data = koreksi::whereId($id)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data tidak ditemukan.'
            ], 400);
        }
        return $data;
    }

        public function showdrypertama($id)
    {
        $data = drypertama::whereId($id)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data tidak ditemukan.'
            ], 400);
        }
        return $data;
    }

    public function showmolding($id)
    {
        $data = molding::whereId($id)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data tidak ditemukan.'
            ], 400);
        }
        return $data;
    }

    public function showdrykedua($id)
    {
        $data = drykedua::whereId($id)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, data tidak ditemukan.'
            ], 400);
        }
        return $data;
    }

     public function getCount(Request $request)
        {
        $adding = adding::count();
        $gradding = gradding::count();
        $mandor = mandor::count();
        $koreksi = koreksi::count();
        $drypertama = drypertama::count();
        $molding = molding::count();
        $drykedua = drykedua::count();
        $gradingakhir = gradingakhir::count();
        // $kartustock = drykedua::count();
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => [
                    'adding' => $adding,
                    'gradding' => $gradding,
                    'mandor' => $mandor,
                    'koreksi' => $koreksi,
                    'drypertama' => $drypertama,
                    'molding' => $molding,
                    'drykedua' => $drykedua,
                    'gradingakhir' => $gradingakhir,
                    'kartustock' => $gradingakhir
                ]
            ], Response::HTTP_OK);
    }

     public function filterKodepartaiAdding(Request $request){
        $data = $request->get('data');
        $filter = adding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->get();
        $sbwsum = adding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_sbw_kotor');
        $pcssum = adding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_pcs');
        $boxsum = adding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function showgradingakhir($id)
        {
            $data = gradingakhir::whereId($id)->first();
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, data tidak ditemukan.'
                ], 400);
            }
            return $data;
        }

    public function showstreaming($id)
        {
            $data = pemanas::whereId($id)->first();
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, data tidak ditemukan.'
                ], 400);
            }
            return $data;
        }
        public function showpacking($id)
        {
            $data = packing::whereId($id)->first();
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, data tidak ditemukan.'
                ], 400);
            }
            return $data;
        }

    public function filterKodepartaiGrading(Request $request){
        $data = $request->get('data');
        $filter = gradding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->get();
        $sbwsum = gradding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = gradding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = gradding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_box');
        if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }
    public function filterKodepartaiMandor(Request $request){
        $data = $request->get('data');
        $filter = mandor::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->get();
        $sbwsum = mandor::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = mandor::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = mandor::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

     public function filterKodepartaiKoreksi(Request $request){
        $data = $request->get('data');
        $filter = koreksi::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->get();
        $sbwsum = koreksi::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = koreksi::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = koreksi::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterKodepartaiDryPertama(Request $request){
        $data = $request->get('data');
        $filter = drypertama::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->get();
        $sbwsum = drypertama::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = drypertama::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = drypertama::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterKodepartaiMolding(Request $request){
        $data = $request->get('data');
        $filter = molding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->get();
        $sbwsum = molding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = molding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = molding::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterKodepartaiDryKedua(Request $request){
        $data = $request->get('data');
        $filter = drykedua::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->get();
        $sbwsum = drykedua::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_sbw');
        $pcssum = drykedua::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_keping');
        $boxsum = drykedua::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                'boxTotal' => $boxsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterKodeSeriGradeakhir(Request $request){
        $data = $request->get('data');
        $filter = gradingakhir::where('kode_transaksi_grading', 'like', "{$data}")->orderBy('id', 'DESC')->get();
        $sbwsum = gradingakhir::where('kode_transaksi_grading', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_sbw_grading');
        $pcssum = gradingakhir::where('kode_transaksi_grading', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_pcs');
        // $boxsum = drykedua::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                // 'boxTotal' => $boxsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }
    public function filterKodePartaiGradeakhir(Request $request){
        $data = $request->get('data');
        $filter = gradingakhir::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->get();
        $sbwsum = gradingakhir::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_sbw_grading');
        $pcssum = gradingakhir::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_pcs');
        // $boxsum = drykedua::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'pcsTotal' => $pcssum,
                // 'boxTotal' => $boxsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterSeriGradeakhirKartuStock(Request $request){
        $data = $request->get('data');
        $filter = DB::table('transaksi_data_grading_akhir')
                        ->where('transaksi_data_grading_akhir.kode_transaksi_grading', 'like', "{$data}")
                        ->leftjoin('streaming','streaming.kode_transaksi_grading' , '=',  'transaksi_data_grading_akhir.kode_transaksi_grading')
                        ->select('transaksi_data_grading_akhir.*',
                            'streaming.status_jual as status_jual')
                        ->orderBy('id', 'DESC')
                        ->get();
        $sbwsum = DB::table('transaksi_data_grading_akhir')
                        ->where('transaksi_data_grading_akhir.kode_transaksi_grading', 'like', "{$data}")
                        ->leftjoin('streaming','streaming.kode_transaksi_grading' , '=',  'transaksi_data_grading_akhir.kode_transaksi_grading')
                        ->orderBy('id', 'DESC')
                        ->sum('transaksi_data_grading_akhir.jumlah_sbw_grading');
          if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterSeriGradeAkhir(Request $request){
        $data = $request->get('data');
            $gradeakhir = DB::table('transaksi_data_grading_akhir')
                            ->where('kode_transaksi_grading', 'like', "{$data}")

                            ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
                            ->leftjoin('mandor', 'mandor.id', '=', 'dry_kedua.mandor_id')
                            ->leftjoin('gradding', 'gradding.id', '=', 'mandor.gradding_id')
                            ->leftjoin('adding', 'adding.id', '=', 'mandor.adding_id')
                            //  ->leftjoin('packing', 'packing.grade_akhir_id', '=', 'transaksi_data_grading_akhir.id')
                            ->leftjoin('master_rumah_walet', 'master_rumah_walet.nama', '=', 'adding.no_register')
                             ->select('transaksi_data_grading_akhir.*', 
                            'gradding.id as gradding_id', 
                            'gradding.jumlah_sbw as gradding_jumlah_sbw', 
                            'gradding.jumlah_keping as gradding_jumlah_keping', 
                            'dry_kedua.tanggal_proses as tanggal_proses_dry_kedua', 
                            'dry_kedua.id as dry_kedua_id' ,
                            'dry_kedua.jumlah_sbw as dry_kedua_jumlah_sbw' ,
                            'mandor.id as mandor_id',
                            'mandor.tanggal_proses as mandor_tanggal_proses',
                            'mandor.jumlah_sbw as mandor_berat_sbw',
                            'adding.id as adding_id',
                            'adding.tanggal_panen as adding_tanggal_panen',
                            'adding.tanggal_penerima as adding_tanggal_penerima',
                            'adding.no_register as adding_nama_rumah_walet',
                            'adding.jumlah_sbw_kotor as adding_berat_sbw_kotor',
                            'master_rumah_walet.no_register as adding_no_register',
                            //== 'packing.tanggal_pengiriman as tanggal_pengiriman'
                            )
                            ->orderBy('id', 'DESC')
                            ->get();

          if ($gradeakhir){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'data' => $gradeakhir
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

     public function getAllTracebility(Request $request){
        $data = $request->get('data');
            $gradeakhir = DB::table('transaksi_data_grading_akhir')
                            ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
                            ->leftjoin('mandor', 'mandor.id', '=', 'dry_kedua.mandor_id')
                            ->leftjoin('gradding', 'gradding.id', '=', 'mandor.gradding_id')
                            ->leftjoin('adding', 'adding.id', '=', 'mandor.adding_id')
                            ->leftjoin('master_rumah_walet', 'master_rumah_walet.nama', '=', 'adding.no_register')
                            ->leftjoin('packing', 'packing.grade_akhir_id', '=', 'transaksi_data_grading_akhir.id')
                            ->leftjoin('streaming', 'streaming.kode_transaksi_grading', '=', 'transaksi_data_grading_akhir.kode_transaksi_grading')
                            // ->(('gradding.jumlah_sbw' - 'dry_kedua.jumlah_sbw')/'gradding.jumlah_sbw' )
                            ->select('transaksi_data_grading_akhir.*', 
                            'gradding.id as gradding_id', 
                            'gradding.jumlah_sbw as gradding_jumlah_sbw', 
                            'gradding.jumlah_keping as gradding_jumlah_keping', 
                            'dry_kedua.tanggal_proses as tanggal_proses_dry_kedua', 
                            'dry_kedua.id as dry_kedua_id' ,
                            'dry_kedua.jumlah_sbw as dry_kedua_jumlah_sbw' ,
                            'mandor.id as mandor_id',
                            'mandor.tanggal_proses as mandor_tanggal_proses',
                            'mandor.jumlah_sbw as mandor_berat_sbw',
                            'adding.id as adding_id',
                            'adding.tanggal_panen as adding_tanggal_panen',
                            'adding.tanggal_penerima as adding_tanggal_penerima',
                            'adding.no_register as adding_nama_rumah_walet',
                            'adding.jumlah_sbw_kotor as adding_berat_sbw_kotor',
                            'master_rumah_walet.no_register as adding_no_register',
                            'streaming.tanggal_proses as tanggal_pengiriman',
                            DB::raw('(adding.jumlah_sbw_kotor - gradding.jumlah_sbw) as susut_sortir'),
                            DB::raw('((gradding.jumlah_sbw - dry_kedua.jumlah_sbw) / (gradding.jumlah_sbw / 100)) as persentasi_susut')
                            )
                            ->orderBy('id', 'DESC')
                            ->paginate(1000); // for pagination
                            // ->get();

            $sumjumlahKepingAwal = DB::table('transaksi_data_grading_akhir')
                            ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
                            ->leftjoin('mandor', 'mandor.id', '=', 'dry_kedua.mandor_id')
                            ->leftjoin('gradding', 'gradding.id', '=', 'mandor.gradding_id')
                            ->orderBy('id', 'DESC')
                            ->sum('gradding.jumlah_keping');

            $sumjumlahKepingAkhir = DB::table('transaksi_data_grading_akhir')
                            ->orderBy('id', 'DESC')
                            ->sum('transaksi_data_grading_akhir.jumlah_pcs');

            $sumberatpenjualan = DB::table('transaksi_data_grading_akhir')
                            ->orderBy('id', 'DESC')
                            ->sum('transaksi_data_grading_akhir.jumlah_sbw_grading');

          if ($gradeakhir){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sumjumlahKepingAwal' => $sumjumlahKepingAwal,
                'sumjumlahKepingAkhir' => $sumjumlahKepingAkhir,
                'sumberatpenjualan' => $sumberatpenjualan,
                'data' => $gradeakhir
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterPartaiTracebility(Request $request){
        $data = $request->get('data');
            $gradeakhir = DB::table('transaksi_data_grading_akhir')
                            ->where('transaksi_data_grading_akhir.kode_partai', 'like', "{$data}")

                            ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
                            ->leftjoin('mandor', 'mandor.id', '=', 'dry_kedua.mandor_id')
                            ->leftjoin('gradding', 'gradding.id', '=', 'mandor.gradding_id')
                            ->leftjoin('adding', 'adding.id', '=', 'mandor.adding_id')
                            ->leftjoin('master_rumah_walet', 'master_rumah_walet.nama', '=', 'adding.no_register')
                            ->leftjoin('packing', 'packing.grade_akhir_id', '=', 'transaksi_data_grading_akhir.id')
                            ->leftjoin('streaming', 'streaming.kode_transaksi_grading', '=', 'transaksi_data_grading_akhir.kode_transaksi_grading')

                            // ->(('gradding.jumlah_sbw' - 'dry_kedua.jumlah_sbw')/'gradding.jumlah_sbw' )
                            ->select('transaksi_data_grading_akhir.*', 
                            'gradding.id as gradding_id', 
                            'gradding.jumlah_sbw as gradding_jumlah_sbw', 
                            'gradding.jumlah_keping as gradding_jumlah_keping', 
                            'dry_kedua.tanggal_proses as tanggal_proses_dry_kedua', 
                            'dry_kedua.id as dry_kedua_id' ,
                            'dry_kedua.jumlah_sbw as dry_kedua_jumlah_sbw' ,
                            'mandor.id as mandor_id',
                            'mandor.tanggal_proses as mandor_tanggal_proses',
                            'mandor.jumlah_sbw as mandor_berat_sbw',
                            'adding.id as adding_id',
                            'adding.tanggal_panen as adding_tanggal_panen',
                            'adding.tanggal_penerima as adding_tanggal_penerima',
                            'adding.no_register as adding_nama_rumah_walet',
                            'adding.jumlah_sbw_kotor as adding_berat_sbw_kotor',
                            'master_rumah_walet.no_register as adding_no_register',
                            'streaming.tanggal_proses as tanggal_pengiriman',
                            DB::raw('(adding.jumlah_sbw_kotor - gradding.jumlah_sbw) as susut_sortir'),
                            DB::raw('((gradding.jumlah_sbw - dry_kedua.jumlah_sbw) / (gradding.jumlah_sbw / 100)) as persentasi_susut')
                            )
                            ->orderBy('id', 'DESC')
                            ->get();

            // $sumjumlahKepingAwal = DB::table('transaksi_data_grading_akhir')
            //                 ->where('transaksi_data_grading_akhir.kode_partai', 'like', "{$data}")
            //                 ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
            //                 ->leftjoin('mandor', 'mandor.id', '=', 'dry_kedua.mandor_id')
            //                 ->leftjoin('gradding', 'gradding.id', '=', 'mandor.gradding_id')
            //                 ->orderBy('id', 'DESC')
            //                 ->sum('gradding.jumlah_keping');
            $beratKotor = DB::table('transaksi_data_grading_akhir')
                            ->where('transaksi_data_grading_akhir.kode_partai', 'like', "{$data}")

                            ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
                            ->leftjoin('mandor', 'mandor.id', '=', 'dry_kedua.mandor_id')
                            ->leftjoin('gradding', 'gradding.id', '=', 'mandor.gradding_id')
                            ->leftjoin('adding', 'adding.id', '=', 'mandor.adding_id')
                            ->select('jumlah_sbw_kotor')
                            ->first();
            // $beratAkhirSortir = DB::table('transaksi_data_grading_akhir')
            //                 ->where('transaksi_data_grading_akhir.kode_partai', 'like', "{$data}")

            //                 ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
            //                 ->leftjoin('mandor', 'mandor.id', '=', 'dry_kedua.mandor_id')
            //                 ->leftjoin('gradding', 'gradding.id', '=', 'mandor.gradding_id')
            //                 // ->select('jumlah_sbw ')
            //                 ->first();
            $sumjumlahKepingAwal = DB::table('gradding')
                            ->where('gradding.kode_partai', 'like', "{$data}")
                            // ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
                            // ->leftjoin('mandor', 'mandor.id', '=', 'dry_kedua.mandor_id')
                            // ->leftjoin('gradding', 'gradding.id', '=', 'mandor.gradding_id')
                            ->orderBy('id', 'DESC')
                            ->sum('gradding.jumlah_keping');

            $sumjumlahKepingAkhir = DB::table('transaksi_data_grading_akhir')
                            ->where('transaksi_data_grading_akhir.kode_partai', 'like', "{$data}")
                            ->orderBy('id', 'DESC')
                            ->sum('transaksi_data_grading_akhir.jumlah_pcs');

            $sumberatpenjualan = DB::table('transaksi_data_grading_akhir')
                            ->where('transaksi_data_grading_akhir.kode_partai', 'like', "{$data}")
                            ->orderBy('id', 'DESC')
                            ->sum('transaksi_data_grading_akhir.jumlah_sbw_grading');

            $sumgrading = DB::table('gradding')
                            ->where('gradding.kode_partai', 'like', "{$data}")
                            ->orderBy('id', 'DESC')
                            ->sum('gradding.jumlah_sbw');

            $sumadding = DB::table('adding')
                            ->where('adding.kode_partai', 'like', "{$data}")
                            ->orderBy('id', 'DESC')
                            ->sum('adding.jumlah_sbw_kotor');

            $susutsortir = $sumadding - $sumgrading;


          if ($gradeakhir){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'beratKotor' => $beratKotor,
                // 'beratAkhirSortir' => $beratAkhirSortir,
                'sumjumlahKepingAwal' => $sumjumlahKepingAwal,
                'sumjumlahKepingAkhir' => $sumjumlahKepingAkhir,
                'sumberatpenjualan' => $sumberatpenjualan,
                'susutsortir' => $susutsortir,
                'data' => $gradeakhir
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterJenisGradeakhir(Request $request){
        $data = $request->get('data');
        $filter = gradingakhir::where('name_jenis_garding', 'like', "{$data}")->orderBy('id', 'DESC')->get();
        $sbwsum = gradingakhir::where('name_jenis_garding', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_sbw_grading');
        // $pcssum = drykedua::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_keping');
        // $boxsum = drykedua::where('kode_partai', 'like', "{$data}")->orderBy('id', 'DESC')->sum('jumlah_box');
          if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                // 'pcsTotal' => $pcssum,
                // 'boxTotal' => $boxsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function filterJenisGradeakhirKartuStock(Request $request){
        $data = $request->get('data');

        $filter = DB::table('transaksi_data_grading_akhir')
                        ->where('transaksi_data_grading_akhir.name_jenis_garding', 'like', "{$data}")
                        ->leftjoin('streaming','streaming.kode_transaksi_grading' , '=',  'transaksi_data_grading_akhir.kode_transaksi_grading')
                        ->select('transaksi_data_grading_akhir.*',
                            'streaming.status_jual as status_jual')
                        ->orderBy('id', 'DESC')
                        ->get();
        $sbwsum = DB::table('transaksi_data_grading_akhir')
                        ->where('transaksi_data_grading_akhir.name_jenis_garding', 'like', "{$data}")
                        ->leftjoin('streaming','streaming.kode_transaksi_grading' , '=',  'transaksi_data_grading_akhir.kode_transaksi_grading')
                        ->orderBy('id', 'DESC')
                        ->sum('transaksi_data_grading_akhir.jumlah_sbw_grading');
          if ($filter){
            return response()->json([
                'success' => true,
                'message' => 'Data ditemukan',
                'sbwTotal' => $sbwsum,
                // 'pcsTotal' => $pcssum,
                // 'boxTotal' => $boxsum,
                'data' => $filter
            ],  200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Data Kosong',
            ],  200);
        }
    }

    public function getLoadDrykedua(){
        return drykedua::orderBy('id', 'DESC')->get();
    }
     
    public function kurangistock(Request $request)
        {
            $id= $request->get('id');
            $jumlah_sbw_grading= $request->get('jumlah_sbw_grading');
            $filter = drykedua::where('id', 'like', "{$id}")->first();
            $lebihbesar = $jumlah_sbw_grading >= $filter->jumlah_sbw_saldo;
            $filter->jumlah_sbw_saldo = ($filter->jumlah_sbw_saldo - $jumlah_sbw_grading);
            // $drykedua->jumlah_keping_saldo = ($drykedua->jmlh_keping_saldo - $request->jumlah_keping);

            // $data= $request->get('data');
            // $response =  gradingakhir::insert(json_decode($data, true)); // Eloquent approach
            if ($lebihbesar){
                return response()->json([
                                'code' => 2,
                                'success' => false,
                                'message' => 'Jumlah yang anda inputkan melebihi stock',
                            ], Response::HTTP_OK);

            }else{
                $filter->update();

                return response()->json([
                                'code' => 1,
                                'success' => true,
                                'message' => 'Data berhasil diupdate!',
                            ], Response::HTTP_OK);
            }
            
        }

        public function restorestock(Request $request)
        {
            $id= $request->get('id');
            $jumlah_sbw_grading= $request->get('jumlah_sbw_grading');
            $filter = drykedua::where('id', 'like', "{$id}")->first();
            $filter->jumlah_sbw_saldo = ($filter->jumlah_sbw_saldo + $jumlah_sbw_grading);
            // $drykedua->jumlah_keping_saldo = ($drykedua->jmlh_keping_saldo - $request->jumlah_keping);
            $filter->update();

            // $data= $request->get('data');
            // $response =  gradingakhir::insert(json_decode($data, true)); // Eloquent approach
            return response()->json([
                'code' => 1,
                'success' => true,
                'message' => 'Data berhasil diupdate!',
            ], Response::HTTP_OK);
        }

        public function filterDatePartaimandor(Request $request){
            $tanggal = $request->tanggal;
            $partai = $request->partai;
            $filterDate = mandor::where('tanggal_proses', 'like', "%{$tanggal}%"  )->where('kode_partai','like', "%{$partai}%")->orderBy('id', 'DESC')->get();
            $sbwsum = mandor::where('tanggal_proses', 'like', "%{$tanggal}%"  )->where('kode_partai','like', "%{$partai}%")->orderBy('id', 'DESC')->sum('jumlah_sbw');
            $pcssum = mandor::where('tanggal_proses', 'like', "%{$tanggal}%"  )->where('kode_partai','like', "%{$partai}%")->orderBy('id', 'DESC')->sum('jumlah_keping');
            $boxsum = mandor::where('tanggal_proses', 'like', "%{$tanggal}%"  )->where('kode_partai','like', "%{$partai}%")->orderBy('id', 'DESC')->sum('jumlah_box');
            if ($filterDate){
                return response()->json([
                    'success' => true,
                    'message' => 'Data ditemukan',
                    'sbwTotal' => $sbwsum,
                    'pcsTotal' => $pcssum,
                    'boxTotal' => $boxsum,
                    'data' => $filterDate
                ],  200);
                
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Data Kosong',
                ],  200);
            }
        }

    public function packinglist(packing $packing)
    {
        $data = DB::table('packing')
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
                ->leftjoin('mandor','mandor.id' , '=',  'dry_kedua.mandor_id')
                ->leftjoin('master_rumah_walet','master_rumah_walet.nama' , '=',  'transaksi_data_grading_akhir.kode_register')
                ->select('packing.*', 
                            'transaksi_data_grading_akhir.id as transaksi_data_grading_akhir_id',
                            'transaksi_data_grading_akhir.kode_transaksi as kode_transaksi_grading_pertama',
                            'transaksi_data_grading_akhir.kode_register as kode_register',
                            'transaksi_data_grading_akhir.kode_partai as kode_partai',
                            'transaksi_data_grading_akhir.jumlah_sbw_grading as jumlah_sbw_grading_akhir',
                            'transaksi_data_grading_akhir.jumlah_pcs as jumlah_pcs_grading_akhir',
                            'transaksi_data_grading_akhir.name_jenis_garding as name_jenis_grading_akhir',
                            'dry_kedua.id as dry_kedua_id',
                            'mandor.id as mandor_id',
                            'mandor.tanggal_proses as mandor_tanggal_proses',
                            'master_rumah_walet.no_register as no_register',
                            DB::raw('((transaksi_data_grading_akhir.jumlah_sbw_grading * packing.box) / 1000) as net_weight_kg')
                           )
                ->orderBy('id', 'DESC')
                ->get();
         $sumQuantity = DB::table('packing')
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                ->orderBy('id', 'DESC')
                ->sum('packing.box');
        
        $sumNetWeight = DB::table('packing')
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                ->orderBy('id', 'DESC')
                ->sum(DB::raw('((transaksi_data_grading_akhir.jumlah_sbw_grading *  packing.box) / 1000)'));

        return response()->json([
            'success' => true,
            'message' => 'data get successfully',
            'sumQuantity' => $sumQuantity,
            'sumNetWeight' => $sumNetWeight,
            'data' => $data
        ], Response::HTTP_OK);
    }

    public function filterkodepartaipackinglist( Request $request)
    {
        $filter = $request->get('data');
        $data = DB::table('packing')
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                ->where('transaksi_data_grading_akhir.kode_partai', 'like', "{$filter}")
                ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
                ->leftjoin('mandor','mandor.id' , '=',  'dry_kedua.mandor_id')
                ->leftjoin('master_rumah_walet','master_rumah_walet.nama' , '=',  'transaksi_data_grading_akhir.kode_register')
                ->select('packing.*', 
                            'transaksi_data_grading_akhir.id as transaksi_data_grading_akhir_id',
                            'transaksi_data_grading_akhir.kode_transaksi as kode_transaksi_grading_pertama',
                            'transaksi_data_grading_akhir.kode_register as kode_register',
                            'transaksi_data_grading_akhir.kode_partai as kode_partai',
                            'transaksi_data_grading_akhir.jumlah_sbw_grading as jumlah_sbw_grading_akhir',
                            'transaksi_data_grading_akhir.jumlah_pcs as jumlah_pcs_grading_akhir',
                            'transaksi_data_grading_akhir.name_jenis_garding as name_jenis_grading_akhir',
                            'dry_kedua.id as dry_kedua_id',
                            'mandor.id as mandor_id',
                            'mandor.tanggal_proses as mandor_tanggal_proses',
                            'master_rumah_walet.no_register as no_register',
                            DB::raw('((transaksi_data_grading_akhir.jumlah_sbw_grading *  packing.box) / 1000) as net_weight_kg')
                           )
                ->orderBy('id', 'DESC')
                ->get();
        $sumQuantity = DB::table('packing')
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                ->where('transaksi_data_grading_akhir.kode_partai', 'like', "{$filter}")
                ->orderBy('id', 'DESC')
                ->sum('packing.box');
        
        $sumNetWeight = DB::table('packing')
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                ->where('transaksi_data_grading_akhir.kode_partai', 'like', "{$filter}")
                ->orderBy('id', 'DESC')
                ->sum(DB::raw('((transaksi_data_grading_akhir.jumlah_sbw_grading *  packing.box) / 1000)'));
        
        return response()->json([
            'success' => true,
            'message' => 'data get successfully',
            'sumQuantity' => $sumQuantity,
            'sumNetWeight' => $sumNetWeight,
            'data' => $data
        ], Response::HTTP_OK);
    }

    public function filtergradeakhirpackinglist( Request $request)
    {
        $filter = $request->get('data');
        $data = DB::table('packing')
                ->where('packing.kode_transaksi_grading', 'like', "{$filter}")
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
                ->leftjoin('mandor','mandor.id' , '=',  'dry_kedua.mandor_id')
                ->leftjoin('master_rumah_walet','master_rumah_walet.nama' , '=',  'transaksi_data_grading_akhir.kode_register')
                ->select('packing.*', 
                            'transaksi_data_grading_akhir.id as transaksi_data_grading_akhir_id',
                            'transaksi_data_grading_akhir.kode_transaksi as kode_transaksi_grading_pertama',
                            'transaksi_data_grading_akhir.kode_register as kode_register',
                            'transaksi_data_grading_akhir.kode_partai as kode_partai',
                            'transaksi_data_grading_akhir.jumlah_sbw_grading as jumlah_sbw_grading_akhir',
                            'transaksi_data_grading_akhir.jumlah_pcs as jumlah_pcs_grading_akhir',
                            'transaksi_data_grading_akhir.name_jenis_garding as name_jenis_grading_akhir',
                            'dry_kedua.id as dry_kedua_id',
                            'mandor.id as mandor_id',
                            'mandor.tanggal_proses as mandor_tanggal_proses',
                            'master_rumah_walet.no_register as no_register',
                            DB::raw('((transaksi_data_grading_akhir.jumlah_sbw_grading *  packing.box) / 1000) as net_weight_kg')
                           )
                ->orderBy('id', 'DESC')
                ->get();
        $sumQuantity = DB::table('packing')
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                ->where('packing.kode_transaksi_grading', 'like', "{$filter}")
                ->orderBy('id', 'DESC')
                ->sum('packing.box');
        
        $sumNetWeight = DB::table('packing')
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                 ->where('packing.kode_transaksi_grading', 'like', "{$filter}")
                ->orderBy('id', 'DESC')
                ->sum(DB::raw('((transaksi_data_grading_akhir.jumlah_sbw_grading *  packing.box) / 1000)'));
        return response()->json([
            'success' => true,
            'message' => 'data get successfully',
            'sumQuantity' => $sumQuantity,
            'sumNetWeight' => $sumNetWeight,
            'data' => $data
        ], Response::HTTP_OK);
    }

    public function filterdatepackinglist( Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $data = DB::table('packing')
                ->whereBetween('tanggal_packing', [$from , $to])
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                ->leftjoin('dry_kedua','dry_kedua.id' , '=',  'transaksi_data_grading_akhir.id_dry_kedua')
                ->leftjoin('mandor','mandor.id' , '=',  'dry_kedua.mandor_id')
                ->leftjoin('master_rumah_walet','master_rumah_walet.nama' , '=',  'transaksi_data_grading_akhir.kode_register')
                ->select('packing.*', 
                            'transaksi_data_grading_akhir.id as transaksi_data_grading_akhir_id',
                            'transaksi_data_grading_akhir.kode_transaksi as kode_transaksi_grading_pertama',
                            'transaksi_data_grading_akhir.kode_register as kode_register',
                            'transaksi_data_grading_akhir.kode_partai as kode_partai',
                            'transaksi_data_grading_akhir.jumlah_sbw_grading as jumlah_sbw_grading_akhir',
                            'transaksi_data_grading_akhir.jumlah_pcs as jumlah_pcs_grading_akhir',
                            'transaksi_data_grading_akhir.name_jenis_garding as name_jenis_grading_akhir',
                            'dry_kedua.id as dry_kedua_id',
                            'mandor.id as mandor_id',
                            'mandor.tanggal_proses as mandor_tanggal_proses',
                            'master_rumah_walet.no_register as no_register',
                            DB::raw('((transaksi_data_grading_akhir.jumlah_sbw_grading *  packing.box) / 1000) as net_weight_kg')
                            // DB::raw('((transaksi_data_grading_akhir.jumlah_sbw_grading * transaksi_data_grading_akhir.jumlah_pcs) / 1000) as net_weight_kg')
                           )
                ->orderBy('id', 'DESC')
                ->get();
        
        $sumQuantity = DB::table('packing')
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                ->whereBetween('tanggal_packing', [$from , $to])
                ->orderBy('id', 'DESC')
                ->sum('packing.box');
        
        $sumNetWeight = DB::table('packing')
                ->leftjoin('transaksi_data_grading_akhir','transaksi_data_grading_akhir.id' , '=',  'packing.grade_akhir_id')
                ->whereBetween('tanggal_packing', [$from , $to])
                ->orderBy('id', 'DESC')
                ->sum(DB::raw('((transaksi_data_grading_akhir.jumlah_sbw_grading *  packing.box) / 1000)'));

        return response()->json([
            'success' => true,
            'message' => 'data get successfully',
            'sumQuantity' => $sumQuantity,
            'sumNetWeight' => $sumNetWeight,
            'data' => $data
        ], Response::HTTP_OK);
    }
}
