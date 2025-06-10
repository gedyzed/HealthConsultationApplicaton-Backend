<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\Patients;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class patientController extends Controller
{
    //

    


      //setProfile
public function setProfile(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
            'fullName' => 'nullable|string',
            'gender' => 'nullable|string',
            'aboutMe' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'idImage' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }

        // Find or create new patient instance
        $patient = Patients::firstOrNew(['patient_id' => $request->patient_id]);

        // Upload profile image or use default
        if ($request->hasFile('image')) {
            $imgPath = $request->file('image')->store('public');
            $patient->image = url('storage/' . basename($imgPath));
        } elseif (!$patient->image) {
            $patient->image = url('storage/app/public/photo_2022-07-14_20-13-30.jpg'); // make sure this file exists
        }

        // Upload ID image or use default
        if ($request->hasFile('idImage')) {
            $idImgPath = $request->file('idImage')->store('public');
            $patient->idImage = url('storage/' . basename($idImgPath));
        } elseif (!$patient->idImage) {
            $patient->idImage = url('storage/app/public/photo_2022-07-14_20-13-30.jpg'); // same default as profile image
        }

        // Set fields or fallback defaults
        $patient->fullName = $request->filled('fullName') ? $request->fullName : ($patient->fullName ?? 'Unknown');
        $patient->gender = $request->filled('gender') ? $request->gender : ($patient->gender ?? 'Not specified');
        $patient->aboutMe = $request->filled('aboutMe') ? $request->aboutMe : ($patient->aboutMe ?? '');

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

public function getDocterList(Request $request, $id)
{
    try {
        $doctors = DB::table('appointments')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.doctor_id')
            ->join('users', 'doctors.doctor_id', '=', 'users.user_id')
            ->where('appointments.patient_id', $id)
            ->select(
                'users.user_id',
                'doctors.fullName',
                'users.email'
            )
            ->distinct()
            ->get();

        return response()->json($doctors, 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve doctor list',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
 


