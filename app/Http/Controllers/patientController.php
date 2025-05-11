<?php

namespace App\Http\Controllers;

use App\Models\Patients;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class patientController extends Controller
{
    //





    //setProfile

    public function setProfile(Request $request) {


    

        $validator = Validator::make($request->all(), [
            "fullName" => "required",
            "patient_id" => "required",
            'image' => 'nullable',
            "gender" => "required",
            "idImage" => "nullable",
            "aboutMe" => "required",
        ]);

        if ($validator->fails()) {
            $data = [
                "status" => "failed",
                "data" => $validator->errors(),
            ];
            return response()->json($data, 422);
        } else {
            $patient = new Patients();
            $patient->fullName = $request->fullName;
            $patient->patient_id = $request->patient_id;
            $patient->image = $request->image;
            $patient->gender = $request->gender;
            $patient->idImage = $request->idImage;
            $patient->aboutMe = $request->aboutMe;
            $patient->save();
            return response()->json($patient, 200);
        }
    }


    public function getPatientById(Request $request,$id)
    {

        $user = User::find($id);

        return response()->json([
            "status"=>"success",
            "role"=>$user->role,
            "email"=>$user->email,
            "password"=>$user->role,
            "patient"=>$user->patient,

        ]);
    }

    

}
