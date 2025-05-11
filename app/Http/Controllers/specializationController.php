<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\Specializations;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class specializationController extends Controller
{
    public function specializedDoctors(Required $required, $name)
    {


        $specializations = Specializations::where('name', $name)->get();

        if ($specializations->isEmpty()) {
            return response()->json(['message' => 'Specialization not found'], 404);
        }
        $doctor = [];
        foreach ($specializations as $specialization) {
            $doctor[] = $specialization->doctor;
        }
        return response()->json($doctor);

        $doctorIds = $specializations->pluck('doctor_id');
        $doctors = Doctors::whereIn('id', $doctorIds)->get();

        return response()->json($doctor);
    }
}
