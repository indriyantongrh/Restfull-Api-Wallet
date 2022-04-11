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
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

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
                    'drykedua' => $drykedua
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

    public function getLoadDrykedua(){
        return drykedua::orderBy('id', 'DESC')->get();
    }
     
    public function kurangistock(Request $request)
        {
            $id= $request->get('id');
            $jumlah_sbw_grading= $request->get('jumlah_sbw_grading');
            $filter = drykedua::where('id', 'like', "{$id}")->first();
            $filter->jumlah_sbw_saldo = ($filter->jumlah_sbw_saldo - $jumlah_sbw_grading);
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
}
