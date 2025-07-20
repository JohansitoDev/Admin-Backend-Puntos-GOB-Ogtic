<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Validation\ValidationException; 

class LoginController extends Controller
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
      
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
 
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')], 
            ]);
        }

      
        $user = $request->user();

      
        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user, 
            'token' => $token,
        ]);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
       
        $request->user()->currentAccessToken()->delete();

        

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}

