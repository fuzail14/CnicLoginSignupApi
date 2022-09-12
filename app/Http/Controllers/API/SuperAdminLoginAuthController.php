<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;



use App\Models\User;


class SuperAdminLoginAuthController extends Controller
{
    public function register(Request $request)
    {

        $validations = Validator::make($request->all(), [


            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'cnic' => 'required|string|max:191|unique:users,cnic',
            'password' => 'required|string',
            'roleid' => 'required|string',
            'rolename' => 'required|string',


        ]);

        

        
        if ($validations->fails()) {
            return response()->json([

                'error' => $validations->errors()->all(),
                "success" => false,
            ], 403);
        }
        // $validations = $request->validate([

        //     'firstname' => 'required|string|max:191',
        //     'lastname' => 'required|string|max:191',
        //     'cnic' => 'required|string|max:191|unique:users,cnic',
        //     'password' => 'required|string',
        //     'roleid' => 'required|string',
        //     'rolename' => 'required|string',

        // ]);

        $user = User::create([

            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'cnic' => $request->cnic,
            'password' => Hash::make($request->password),
            'roleid' => $request->roleid,
            'rolename' => $request->rolename,


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


        return response(['message' => 'Logged out Successfully'], 200);
    }

    public function alluser()
    {

        $user = User::all();

        return response($user);
    }





    // public function login(Request $request)
    // {

    //     $loginvalidations = $request->validate([


    //         'cnic' => 'required|string|max:191',
    //         'password' => 'required|string',
    //     ]);

    //     $user = User::where('cnic', $loginvalidations['cnic'])->first();

    //     if (!$user || !Hash::check($loginvalidations['password'], $user->password)) {

    //         return response(['message' => 'Invalid creditanls'], 401);
    //     } else {
    //         $token = $user->createToken('MainAdmin')->plainTextToken;

    //         $response = [

    //             'user' => $user,
    //             'token' => $token,
    //             'success' => true,

    //         ];

    //         return response($response, 200);
    //     }
    // } 



    public function login(Request $request)
    {


        $validator = Validator::make($request->all(), [

            'cnic' => 'required',
            'password' => 'required',

        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        } elseif (Auth::attempt(['cnic' => $request->cnic, 'password' => $request->password])) {

            $user = Auth::user();



            //$success['token'] = $user->createToken('MyApp')->plainTextToken;
            

            $token = $request->user()->createToken('MyApp')->plainTextToken;


            $success = $user;

            $response = [
                'success' => true,

                'data' => $success,
                'token' => $token,
                'message' => 'User login Successfully'
            ];
            return response()->json($response);
        } else if (!Auth::attempt(['cnic' => $request->cnic, 'password' => $request->password])) {


            $response2 = [
                'success' => false,
                'message' => 'UnAthorized'
            ];
            return response()->json($response2, 401);
        }
    }
}
