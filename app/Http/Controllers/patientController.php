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


        $img1 = $request->file('image')->store('public/files');
        $img2 = $request->file('idImage')->store('public/files');

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
            $patient->image = Storage::url($img1);
            $patient->gender = $request->gender;
            $patient->idImage = Storage::url($img2);
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

    public function getDocterList(Request $request , $id){
        $appointments = Appointments::where("patient_id",$id)->get();
        $doctor = [];
        
        foreach ($appointments as $app){
            $doctor[] = $app->doctor;
            
        }
        return response()->json(array_unique($doctor),200);

        }
}
 


