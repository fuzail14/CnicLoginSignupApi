<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\subadminsociety;
use App\Models\Society;

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
            'mobileno' => 'required',
            'address' => 'required|string',

            'cnic' => 'required|string|max:191|unique:users,cnic',
            'password' => 'required|string',
            'roleid' => 'required',
            'rolename' => 'required|string',
            //'image' => 'required',






        ]);






        if ($validations->fails()) {
            return response()->json([

                'error' => $validations->errors()->all(),
                "success" => false,
            ], 403);
        }



        //$imagepath = $request->file('image')->store('public/uploads');
        



        // if($superadminid && $societyid != null )
        // {
        //      $isValidate = Validator::make($request->all(), [
        //             'firstname' => 'required|string|max:191',
        //             'lastname' => 'required|string|max:191',
        //             'mobileno' => 'required',
        //             'address' => 'required',

        //             'cnic' => 'required|unique:users|max:191',
        //             'roleid' => 'required',
        //             'rolename' => 'required',
        //             'password' => 'required',

        //         ]);
        //         if ($isValidate->fails()) {
        //             return response()->json([
        //                 "errors" => $isValidate->errors()->all(),
        //                 "success" => false
        //             ], 403);
        //         }

        //         $society = new Society;




        //         $user = new User;
        //         $user->firstname = $request->firstname;
        //         $user->lastname = $request->lastname;
        //         $user->mobileno = $request->mobileno;
        //         $user->address = $request->address;

        //         $user->cnic = $request->cnic;
        //         $user->roleid = $request->roleid;
        //         $user->rolename = $request->rolename;
        //         $user->password = Hash::make($request->password);
        //         $user->image = $imagepath;
        //         $user->save();



        //         if($superadminid != $user->id && $societyid != $society->id ){

        //           $fin =   $superadminid->findOrFail($user->id);

        //             dd($superadminid ,$user->id ,$societyid ,$society->id,$fin);



        //             return response()->json([
        //                 "errors" => $isValidate->errors()->all(),
        //                 'id' => 'wrong id',
        //                  "success" => false
        //             ], 403);
        //         }





        //             $subadminsocieties = new subadminsociety;
        //             $subadminsocieties->superadminid=$superadminid;
        //             $subadminsocieties-> subadminid=$user->id;
        //             $subadminsocieties-> societyid =$societyid;
        //             $subadminsocieties->save();
        //             $tk =   $user->createToken('token')->plainTextToken;
        //             return response()->json(
        //                 [
        //                     "token" => $tk,
        //                     "success" => true,
        //                     "message" => "User Register Successfully",
        //                     "data" => $user,
        //                 ]
        //             );
        // }





        $isValidate = Validator::make($request->all(), [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'mobileno' => 'required',
            'address' => 'required',

            'cnic' => 'required|unique:users|max:191',
            'roleid' => 'required',
            'rolename' => 'required',
            'password' => 'required',
        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false
            ], 403);
        }
        $user = new User;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->mobileno = $request->mobileno;
        $user->address = $request->address;
        $user->cnic = $request->cnic;
        $user->roleid = $request->roleid;
        $user->rolename = $request->rolename;
        $user->password = Hash::make($request->password);
        //$user->image = $imagepath;
        $user->save();




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
