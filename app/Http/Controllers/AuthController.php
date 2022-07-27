<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    //Create new user 
    public function register(Request $request){

        //Valido los datos 
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|max:25',
            ]);

            //Si el validator falla lanza error
            if($validator->fails()){
            return response()->json([
                'success' => true,
                'message' => $validator->errors()
            ],);
            }

            //Creo el usuario 
            $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->password)
            ]);

            //Creo el token con encryptados los dastos de $user
            $token = JWTAuth::fromUser($user);
            return response()->json(compact('user','token'),201);
    }
}
