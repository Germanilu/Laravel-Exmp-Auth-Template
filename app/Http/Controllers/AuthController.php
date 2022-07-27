<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    //Create new user 
    public function register(Request $request)
    {

        //Valido los datos 
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|max:25',
        ]);

        //Si el validator falla lanza error
        if ($validator->fails()) {
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
        return response()->json(compact('user', 'token'), 201);
    }



    //Login User
    public function login(Request $request)
    {
        //Requiero por body email y password (la propiedad only recupera solo lo q digo entre parentesis)
        $input = $request->only('email', 'password');
        //Setea la variable jwt a null
        $jwt_token = null;


        //Aqui con el attempt revisa el $input y comprueba la contraseña y el usuario si ambos son validos me genera 1 token para ese usuario git
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }


    //Get profile
    public function me(){
        return response()->json([
            'success' => true,
            'data' => auth()->user()
        ]);
    }

    //Logout (El logout pasa x el body el token)
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        try {
            JWTAuth::invalidate($request->token);
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
