<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\Doctors;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class docterController extends Controller
{
    //


    



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


    public function setDoctorProfile(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required',
            'fullName' => 'required',
            'aboutMe' => 'required',
            'yearOfExperience' => 'required',
            'pricing' => 'required',
            'image' => 'required',
            'idImage' => 'required',
            'languages' => 'required',
            'specializations' => 'array',
            'education' => 'array',
            'education.*.degree' => 'required',
            'education.*.fieldOfStudy' => 'required',
            'education.*.institution' => 'required',
            'education.*.endYear' => 'required',
            'certifications' => 'required',
        ]);
        
        
        
        
        
        $user = User::findOrFail($request->doctor_id);
        
        
        
        $user->doctor->create($request->only([
            'fullName',
            'aboutMe',
            'yearOfExperience',
            'pricing',
            'image',
            'idImage'
        ]));
        return response()->json([$user->doctor],200);



        foreach ($request->specializations as $spec) {
            $user->doctor->specializations()->create(['name' => $spec]);
        }


        foreach ($request->languages as $lang) {
            $user->doctor->languages()->create(['language' => $lang]);
        }


        foreach ($request->education as $edu) {
            $user->doctor->educations()->create([
                'degree' => $edu['degree'],
                'fieldOfStudy' => $edu['fieldOfStudy'],
                'institution' => $edu['institution'],
                'endYear' => $edu['endYear'],
            ]);
        }


        foreach ($request->certifications as $cert) {
            $user->doctor->certificates()->create(['certificate_image' => $cert]);
        }

        return response()->json(['status' => 200, 'message' => 'Profile updated successfully'], 200);
    }


    public function getPatientList(Request $request , $id){
        $appointments = Appointments::where("doctor_id",$id)->get();
        $patient = [];
        
        foreach ($appointments as $app){
            $patient[] = $app->patient;
            
        }
        return response()->json(array_unique($patient),200);

        }
}
