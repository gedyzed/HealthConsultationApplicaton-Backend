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
public function setProfile(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'name' => 'nullable|string',
            'gender' => 'nullable|string',
            'about' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'idImage' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }

        // Find or create a new patient instance (not saved yet)
        $patient = Patient::firstOrNew(['id' => $request->patient_id]);

        // Handle profile image upload
        if ($request->hasFile('image')) {
            $imgPath = $request->file('image')->store('public');
            $patient->image = url('storage/' . basename($imgPath));
        }

        // Handle ID image upload
        if ($request->hasFile('idImage')) {
            $idImgPath = $request->file('idImage')->store('public');
            $patient->idImage = url('storage/' . basename($idImgPath));
        }

        // Update optional fields
        if ($request->filled('name')) {
            $patient->name = $request->name;
        }

        if ($request->filled('gender')) {
            $patient->gender = $request->gender;
        }

        if ($request->filled('about')) {
            $patient->about = $request->about;
        }

        $patient->save();

        return response()->json([
            'status' => 200,
            'message' => 'Profile saved successfully',
            'data' => $patient,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Something went wrong',
            'error' => $e->getMessage(),
        ], 500);
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
 


