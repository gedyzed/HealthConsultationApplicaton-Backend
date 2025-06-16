<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\Doctors;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



class docterController extends Controller
{
    //

    public function getUserWithUnverifiedDoctor($id): 
{
    $user = User::whereHas('doctors', function ($query) {
            $query->where('status', 'unverified');
        })
        ->with(['doctors' => function ($query) {
            $query->where('status', 'unverified')
                ->with([
                    'languages', 
                    'certificates', 
                    'educations', 
                    'specializations', 
                    'appointments'
                ]);
        }])
        ->find($id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found or doctor is verified',
            'data' => null
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $user
    ]);
}


    public function getDoctorById(Request $request, $id)
    {

        $user = User::find($id);

        return response()->json([
            "status" => "success",
            "role" => $user->role,
            "email" => $user->email,
            "password" => $user->role,
            "docter"=>$user->doctor
            

        ]);
    }

    public function getDoctors(Request $request)
    {
        $user = Doctors::all();
        return response()->json([$user]);
    }

    public function getProfileById(Request $request, $id){
        $user = User::find($id);
        $user->doctor;
        $user->doctor->languages;
        $user->doctor->certificates;
        $user->doctor->educations;
        $user->doctor->specializations;
        $user->doctor->appointments;
        return response()->json([$user]);
        
    }



    public function getCommentsOfDoctor(Request $request, $id)
    {

        $appointments = Appointments::with(['patient', 'comment'])
            ->where('doctor_id', $id)
            ->get();

        $results = [];

        foreach ($appointments as $appointment) {
            if ($appointment->comment) {
                $results[] = [
                    'patient_name' => $appointment->patient->fullName,
                    'patient_photo' => $appointment->patient->photo,
                    'comment' => $appointment->comment->comment,
                    'rating' => $appointment->comment->rating,
                    'appointment_date' => $appointment->appointmentDate,
                ];
            }
        }

        return response()->json($results);
    }


public function setProfile(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            "fullName" => "required",
            "doctor_id" => "required",
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2948',
            "idImage" => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2948',
            "aboutMe" => "required",
            "pricing" => "required",
            "yearOfExperience" => "required",
            'languages' => 'required|array',
            'specializations' => 'array',
            'education' => 'array',
            'education.*.degree' => 'required|string',
            'education.*.fieldOfStudy' => 'required|string',
            'education.*.institution' => 'required|string',
            'education.*.endYear' => 'required|integer',
            'certifications' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "failed",
                "data" => $validator->errors(),
            ], 422);
        }

        $doctor = Doctors::where('doctor_id', $request->doctor_id)->first();

        if (!$doctor) {
            $doctor = new Doctors();
            $doctor->doctor_id = $request->doctor_id;
        }

        // If image uploaded, use it. Otherwise set a default image if not already set
        if ($request->hasFile('image')) {
            $imgPath = $request->file('image')->store('public');
            $doctor->image = url('storage/' . basename($imgPath));
        } elseif (!$doctor->image) {
       
            $doctor->image = url('storage/app/public/photo_2022-07-14_20-13-30.jpg');
        }

        // Handle ID image
        if ($request->hasFile('idImage')) {
            $idImgPath = $request->file('idImage')->store('public');
            $doctor->idImage = url('storage/' . basename($idImgPath));
        }

        // Set other fields
        $doctor->fullName = $request->fullName;
        $doctor->yearOfExperience = $request->yearOfExperience;
        $doctor->pricing = $request->pricing;
        $doctor->aboutMe = $request->aboutMe;

        $doctor->save();

        // Clear existing related data
        $doctor->specializations()->delete();
        $doctor->languages()->delete();
        $doctor->educations()->delete();
        $doctor->certificates()->delete();

        // Save specializations
        foreach ($request->get('specializations', []) as $spec) {
            $doctor->specializations()->create(['name' => $spec]);
        }

        // Save languages
        foreach ($request->get('languages', []) as $lang) {
            $doctor->languages()->create(['language' => $lang]);
        }

        // Save education
        foreach ($request->get('education', []) as $edu) {
            $doctor->educations()->create([
                'degree' => $edu['degree'],
                'fieldOfStudy' => $edu['fieldOfStudy'],
                'institution' => $edu['institution'],
                'endYear' => $edu['endYear'],
            ]);
        }

        // Save certifications
        foreach ($request->get('certifications', []) as $cert) {
            $doctor->certificates()->create(['certificate_image' => $cert]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Profile updated successfully',
            'data' => $doctor
        ], 200);

    } catch (Exception $e) {
        return response()->json([
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}




public function getPatientList(Request $request, $doctor_id)
{
    $patients = DB::table('appointments') 
        ->join('patients', 'appointments.patient_id', '=', 'patients.patient_id') 
        ->join('users', 'patients.patient_id', '=', 'users.user_id') 
        ->where('appointments.doctor_id', $doctor_id)  
        ->select(
            'users.user_id', 
            'patients.fullName',
            'patients.patient_id', 
            'users.email' 
        )
        ->distinct()
        ->get();

    return response()->json($patients, 200);
}






    public function editProfile(Request $request, $id)
    {
        $request->validate([
            'fullName' => 'required|string',
            'aboutMe' => 'required|string',
            'pricing' => 'required|numeric',
            'yearOfExperience' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2948',
            'idImage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2948',
            'languages' => 'array',
            'specializations' => 'array',
            'education' => 'array',
            'education.*.degree' => 'required|string',
            'education.*.fieldOfStudy' => 'required|string',
            'education.*.institution' => 'required|string',
            'education.*.endYear' => 'required|integer',
        ]);

        $doctor = Doctors::findOrFail($id);

        if ($request->hasFile('image')) {
            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
            Storage::disk('public')->put($imageName, file_get_contents($request->image));
            $doctor->image = $imageName;
        }

        $doctor->fullName = $request->fullName;
        $doctor->aboutMe = $request->aboutMe;
        $doctor->pricing = $request->pricing;
        $doctor->yearOfExperience = $request->yearOfExperience;
        $doctor->save();


        $doctor->languages()->delete();
        if ($request->filled('languages')) {
            foreach ($request->languages as $lang) {
                $doctor->languages()->create(['language' => $lang]);
            }
        }

        $doctor->specializations()->delete();
        if ($request->filled('specializations')) {
            foreach ($request->specializations as $spec) {
                $doctor->specializations()->create(['name' => $spec]);
            }
        }
        $doctor->educations()->delete();
        if ($request->filled('education')) {
            foreach ($request->education as $edu) {
                $doctor->educations()->create([
                    'degree' => $edu['degree'],
                    'fieldOfStudy' => $edu['fieldOfStudy'],
                    'institution' => $edu['institution'],
                    'endYear' => $edu['endYear'],
                ]);
            }
        }

        return response()->json([
            'status' => 200,
            'message' => 'Profile updated successfully',
        ]);
    }
}
