<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;






class AddSocietyController extends Controller
{
    public function addsociety(Request $request)
    {




        $validations = Validator::make($request->all(), [


            'societyname' => 'required',
            'societyaddress' => 'required',

            'roleid' => 'required|exists:users,id',


        ]);

        if ($validations->fails()) {
            return response()->json([

                'error' => $validations->errors()->all(),
                "success" => false,
            ], 403);
        }


        

        $user = Society::create([

            'societyname' => $request->societyname,
            'societyaddress' => $request->societyaddress,

            'roleid' => $request->roleid,


        ]);


        $response = [

            'user' => $user,

            'success' => true,
        ];

        return response($response, 200);
    }
}
