<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\HasApiTokens;


use App\Models\User;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validations = $request->validate([

            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'cnic' => 'required|string|max:191|unique:users,cnic',
            'password' => 'required|string',
            'roleid' => 'required|string',
            'rolename' => 'required|string',
            
        ]);

        $user = User::create([

            'firstname' => $validations['firstname'],
            'lastname' => $validations['lastname'],
            'cnic' => $validations['cnic'],
            'password' => Hash::make($validations['password']),
            'roleid' => $validations['roleid'], 
            'rolename' => $validations['rolename'],


        ]);

        $token = $user->createToken('MainAdmin')->plainTextToken;
        $response = [

            'user' => $user,
            'token' => $token,
            'success' => true,
        ];

        return response($response, 200);
    }

    public function logout()
    {


        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });


        return response(['message' => 'Logged out Successfully']);
    }

    public function alluser()
    {

        $user = User::all();

        return response($user);

    }

    public function login(Request $request)
    {

        $loginvalidations = $request->validate([


            'cnic' => 'required|string|max:191',
            'password' => 'required|string',
        ]);

        $user = User::where('cnic', $loginvalidations['cnic'])->first();

        if(!$user || !Hash::check($loginvalidations['password'],$user->password))
        {

            return response(['message'=> 'Invalid creditanls'],401);
        }
        else
        {
            $token = $user->createToken('MainAdmin')->plainTextToken;

            $response = [

                'user' => $user,
                'token' => $token,
                'success' => true,
                
            ];

            return response($response,200);



        }
    }


   
}
