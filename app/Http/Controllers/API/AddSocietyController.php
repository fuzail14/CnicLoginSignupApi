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

            'mainadminid' => 'required|exists:users,id',



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

            'mainadminid' => $request->mainadminid,


        ]);


        $response = [

            'user' => $user,

            'success' => true,
        ];

        return response($response, 200);
    }


    public  function updatesociety(Request $request)
    {
        $validations = Validator::make($request->all(), [

            'societyname' => 'required',
            'societyaddress' => 'required',




        ]);
        if ($validations->fails()) {
            return response()->json([
                "errors" => $validations->errors()->all(),
                "success" => false
            ], 403);
        }

        $society = Society::find($request->id);

        $society->societyname = $request->societyname;
        $society->societyaddress = $request->societyaddress;
        $society->save();


        return response()->json([
            "success" => true,
            "data" => $society,
            "message" => "society update successfully"
        ]);
    }


    public function viewallsocieties($mainadminid)
    {

        $society = Society::where('mainadminid', $mainadminid)->get();

        return response()->json(['data' => $society]);
    }

    public function deletesociety($id)
    {



        $society = Society::where('id', $id)->delete();

        if ($society == 0) {

            return response()->json(['data' => $society, "message" => "society doesn't exist "]);
        }


        return response()->json(['data' => $society, "message" => "deleted society successfully"]);
    }

    public function searchsociety($q)
    {
        dd($q);
        $request = Society::where('societyname', 'LIKE', '%' . $q . '%')
        ->orWhere('societyaddress', 'LIKE', '%' . $q . '%')->get();


        //if ($request) {
            return response()->json([
                'data' => $request,

                'message' => 'success',
            ]);
        //}

        // return response()->json([
            

        //     'message' => 'Not Found',
        // ]);
    }
}
