<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\Patients;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class patientController extends Controller
{
    //

    


      //setProfile

    public function setProfile(Request $request) {

        

        $validator = Validator::make($request->all(), [
            "fullName" => "required",
            "patient_id" => "required",
            'image' => 'required',
            "gender" => "required",
            "idImage" => "required",
            "aboutMe" => "required",
        ]);


        $img1 = "storage/".$request->file('image')->store('public');
        $img2 = "storage/".$request->file('idImage')->store('public');

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
            $patient->image =url($img1);
            $patient->gender = $request->gender;
            $patient->idImage = url($img2);
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
            "fullName"=>$user->fullName,
            "email"=>$user->email,
            "password"=>$user->role,
            "patient"=>$user->patient,

        ]);
    }


public function getPatientList(Request $request, $patient_id)
{
    $doctors = DB::table('appointments') 
        ->join('doctors', 'appointments.doctors_id', '=', 'doctors.doctors_id') 
        ->join('users', 'doctors.doctors_id', '=', 'users.user_id') 
        ->where('appointments.patient_id', $patient_id)  
        ->select(
            'users.user_id', 
            'doctors.doctors_id', 
            'doctors.fullName',
            'users.email' 
        )
        ->distinct()
        ->get();

    return response()->json($doctors, 200);
}
}
 


