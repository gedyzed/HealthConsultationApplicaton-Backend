<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class authenticationController extends Controller
{
    //Sign up  post Api
    // api/signUP
    public function signUp(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'role' => 'required',
            "password" => "required"
        ]);



        if ($validator->fails()) {
            $data = [
                "status" => "failed",
                "data" => $validator->errors(),
            ];
            return response()->json($data, 422);
        } else {

            $user = new User();
            $user->email = $request->email;
            $user->role = $request->role;
            $user->password = Hash::make( $request->password);
            
            $token = Auth::login($user);
            $user->save();
            return response()->json([
                "status"=>"success",
                "data"=>$user,
                "Authentication"=>[
                    "token"=>$token,
                    "type"=>"bearer"
                    ]
            ], 200);
        }

    }



    public function login(Request $request)    {
        $credentials = $request->only(['email', 'password']);
        $token = Auth::attempt(credentials: $credentials);
        if (!$token) {
            return response()->json(data: [
                'status' => 'error',
                'message' => 'Unauthorized',
            ], status: 401);
        }

        $user = Auth::user();
        return response()->json(data: [
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }



    public function logout()
    {
        auth::logout(); 

        return response()->json(['message' => 'Successfully logged out']);
    }
}
