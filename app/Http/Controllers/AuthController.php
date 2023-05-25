<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        // dd($request);
       
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $resposeMessage = "Registration Successful";

        return response()->json([
            'success' => true,
            'message' => $resposeMessage
        ],200);  //success
    }
    
    public function login(LoginRequest $request) {

        $credentials = $request->only(['email', 'password']);

        $user = User::where('email', $credentials['email'])->first();

        if($user) {

            if (! auth()->attempt($credentials)) {
                $responseMessage = 'Invalid username or password';
                return response()->json([
                    'success' => false,
                    'message' => $responseMessage,
                    'error' => $responseMessage
                ], 422);
            }

            $accessToken = auth()->user()->createToken('authToken')->accessToken; 

            $responseMessage = "Login Successful";

            return response()->json([
                'success' => true,
                'message' => $responseMessage,
                'token' => $accessToken,
                'token_type' => 'bearer',  //al portatore
                'data' => auth()->user()
            ], 200); //success

        } else {

            $responseMessage = 'Sorry this user does not exist';
            
            return response()->json([
                'success' => false,
                'message' => $responseMessage,
                'error' => $responseMessage,
            ], 422); // utente non esistente // 422 Unprocessable Entity
        }
    }

    public function logout(){
        $user = Auth::guard('api')->user(); // the user must be authenticated 
        
        if (!$user) {

            $responseMessage = 'Invalid Bearer Token';

            return response()->json([
                'success' => false,
                'message' => $responseMessage,
                'error' => $responseMessage
            ], 403); //403 Forbidden
        }

        $token = $user->token();

        $token->revoke();

        $responseMessage = 'successfully logged out';
        
        return response()->json([
            'success' => true,
            'message' => $responseMessage
        ], 200);
    }
}
