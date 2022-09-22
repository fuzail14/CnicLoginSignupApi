<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\subadminsociety;

class SubAdminSocietyController extends Controller
{
    public function   registersubadmin(Request $request)

    {
        $isValidate = Validator::make($request->all(), [
            'firstname' => 'required|string|max:191',
            'lastname' => 'required|string|max:191',
            'cnic' => 'required|unique:users|max:191',
            'address' => 'required',
            'mobileno' => 'required|unique:users|max:191',
            'roleid' => 'required',
            'rolename' => 'required',
            'password' => 'required',
            'superadminid' => 'required|exists:users,id',
            'societyid' => 'required|exists:societies,id',
            //'image' => 'required',


        ]);
        if ($isValidate->fails()) {
            return response()->json([
                "errors" => $isValidate->errors()->all(),
                "success" => false

            ], 403);
        }

        //$imagepath = $request->file('image')->store('public/uploads');
        

        $user = new User;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->cnic = $request->cnic;
        $user->address = $request->address;
        $user->mobileno = $request->mobileno;
        $user->roleid = $request->roleid;
        $user->rolename = $request->rolename;
        //$user->image = $imagepath;

        $user->password = Hash::make($request->password);
        $user->save();
        $tk =   $user->createToken('token')->plainTextToken;
        $subadminsociety = new subadminsociety;
        $subadminsociety->superadminid = $request->superadminid;
        $subadminsociety->societyid = $request->societyid;
        $subadminsociety->subadminid = $user->id;

        $subadminsociety->save();

        return response()->json(
            [
                "token" => $tk,

                "success" => true,
                "message" => "Sub Admin Assign to Society Successfully",
                "data" => $user,
                "data2" => $subadminsociety,
            ]
        );
    }
}
