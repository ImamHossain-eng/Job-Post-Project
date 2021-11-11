<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User;

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = 'user';
        $user->password = bcrypt($request->input('password'));
        
        

        $user->save();

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
        
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
       
        // Check email
        $user = User::where('email', $request->input('email'))->first();

        if(!$user){
            return response([
                'message' => 'Wrong Email'
            ], 401);
        }else{
            if(!Hash::check($request->input('password'), $user->password)){
                return response([
                    'message' => 'Wrong Password'
                ], 401);
            }else{
                $token = $user->createToken('myapptoken')->plainTextToken;

                $response = [
                    'user' => $user,
                    'token' => $token
                ];

                return response($response, 201);
            }
        }

        
    }

    
    
}
