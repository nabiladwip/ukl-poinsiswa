<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
Use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    //fungsi untuk login
    public function login(Request $request){
        $credentials =$request->only('email', 'password');
        try{
            if(!$token = jWTAuth::attempt($credentials)){
                return response()->json([
                    'logged' => false,
                    'message' =>'invalid email or password'
                ]);
            }
        }catch(JWTException $e){
            return response()->json([
                'logged'=> false,
                'message'=> 'generate token failed'
            ]);
        }

        return response()->json([
            "logged" => true,
            "token" => $token,
            "messsage" =>'login berhasil'
        ]);
    }

    public function register(request $request)
    {
        // $validator= Validator::make($request->all(),[
        //     'name' => 'required|string|max:255',
		// 	'email' => 'required|string|email|max:255|unique:users',
		// 	'password' => 'required|string|min:6',
        //     // 'role' => 'required|in:admin,petugas',
        // ]);
        // if($validator->fails()){
        //     return response()->json([
        //         'status'=> 0,
        //         'message'=> $validator->errors()->toJson()
        //     ]);
        // }

        $user = new user();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);
        $user->role     = $request->role;
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status'=>'1',
            'message'=>'User berhasil ter Registrasi'
        ], 201);
    }

    public function getAuthenticatedUser(){
		try {
			if(!$user = JWTAuth::parseToken()->authenticate()){
				return response()->json([
						'auth' 		=> false,
						'message'	=> 'Invalid token'
					]);
			}
		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Token expired'
					], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Invalid token'
					], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\JWTException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Token absent'
					], $e->getStatusCode());
		}
		 return response()->json([
		 		"auth"      => true,
                "user"    => $user
		 ], 201);
    }
    
    public function index($limit = 10, $offset =0)
    {
        $data["count"]= User::count();
        $user = array();

        foreach (User::take($limit)->skip($offset)->get() as $p){
            $item=[
                "id"                =>$p->id,
                "name"              =>$p->name,
                "email"             =>$p->email,
                "password"          =>$p->password,
                "role"              =>$p->role,
                "create_at"         =>now(),
                "update_at"         =>now(),   
                
            ];

            array_push($user, $item);
        }

        $data["user"]= $user;
        $data["status"] = 1;
        return response($data);
    }

    public function store(Request $request)
    {
        $user = new User([
            'name'               => $request->name,
            'email'        => $request->email,
            'password'             => $request->password,
        ]);

        $user->save();
        return response($user);
    }

    public function show($id)
    {
        $user = User::where('id'. $id)->get();

        $dataUser = array();
        foreach($user as $p){
            $item =[
            "id"           => $p->id,
            "name"          => $p->name,
            "email"   => $p->email,
            "password"        => $p->password,
            "role" =>$p->role,
            
            ];

            array_push($dataUser, $item);
        }

        $data["dataUser"] = $dataUser;
        $data["status"] = 1;
        return response($data);
    }

    public function update($id, Request $request)
    {
        // try {
            $data = User::where('id', $id)->first();
            $data->name            = $request->input('name');
            $data->email           = $request->input('email');
            $data->password        = $request->input('password');
            $data->role            = $request->input('role');
            $data->save();
            return response()->json([
                'status' =>'1',
                'message' =>'data berhasil di ubah'
            ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => '0',
        //         'message' =>'Data  gagal diubah'
        //     ]);
        // }
    }

    public function delete($id)
    {
        try{

            User::where("id", $id)->delete();

            return response([
            	"status"	=> 1,
                "message"   => "Data berhasil dihapus."
            ]);
        } catch(\Exception $e){
            return response([
            	"status"	=> 0,
                "message"   => $e->getMessage()
            ]);
        }
    }

}
