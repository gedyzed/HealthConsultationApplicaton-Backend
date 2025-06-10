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
    // Validate input: all fields are optional, and image files are nullable
    $validatedData = $request->validate([
        'name' => 'nullable|string|max:255',
        'gender' => 'nullable|string|in:male,female,other',
        'about' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'idImage' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Assume user is authenticated
    $user = auth()->user();

    // Update text fields only if provided
    if ($request->has('name')) {
        $user->name = $validatedData['name'];
    }

    if ($request->has('gender')) {
        $user->gender = $validatedData['gender'];
    }

    if ($request->has('about')) {
        $user->about = $validatedData['about'];
    }

    // Handle profile image (column name: `image`)
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('profiles', 'public');
        $user->image = $path;
    }

    // Handle ID image (column name: `idImage`)
    if ($request->hasFile('idImage')) {
        $path = $request->file('idImage')->store('ids', 'public');
        $user->idImage = $path;
    }

    // Save changes
    $user->save();

    return response()->json([
        'message' => 'Profile updated successfully',
        'user' => $user,
    ]);
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
 


