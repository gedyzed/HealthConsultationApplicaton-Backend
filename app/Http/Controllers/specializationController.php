<?php

namespace App\Http\Controllers;

use App\Models\Doctors;
use App\Models\Specializations;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Support\Facades\DB;

class specializationController extends Controller
{
    public function specializedDoctor(Required $required, $name)
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


public function specializedDoctors(Request $request, $name)
{
    $doctors = DB::table('doctors')
        ->join('users', 'doctors.doctor_id', '=', 'users.user_id')
        ->join('specializations', 'specializations.doctor_id', '=', 'doctors.doctor_id')
        ->where('specializations.name', $name)
        ->where('doctors.status', 'verified')
        ->select(
            'doctors.doctor_id',
            'users.fullName',
            'users.email',
            'doctors.experience',
            'doctors.image',
            'specializations.name as specialization_name'
        )
        ->get();

    return response()->json($doctors);
}


}
