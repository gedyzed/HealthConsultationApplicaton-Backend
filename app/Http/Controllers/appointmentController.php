<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\Call;
use App\Models\Patients;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class appointmentController extends Controller
{
    //

    public function setAppointment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "doctor_id" => "required",
            "patient_id" => "required",
            'symptoms' => 'nullable',
            "appointmentDate" => "nullable",
            "appointmentTime" => "required",
            "price" => "required",
            "statusAppointment" => "required"
        ]);

        

        if ($validator->fails()) {
            $data = [
                "status" => "failed",
                "data" => $validator->errors(),
            ];
            return response()->json($data, 422);
        } else {


            $appointment = new Appointments();
            $appointment->doctor_id = $request->doctor_id;
            $appointment->patient_id = $request->patient_id;
            $appointment->symptoms = $request->symptoms;
            $appointment->appointmentDate = $request->appointmentDate;
            $appointment->appointmentTime = $request->appointmentTime;
            $appointment->price = $request->price;
            $appointment->statusAppointment = $request->statusAppointment;

            
            $appointment->save();
            return response()->json([
                "status"=>"success",
                "appointment"=>$appointment], 200);
        }
    }


    public function getAppointmetnByPatientId(Request $request, $id)
    {

        $appointments = Appointments::where("patient_id",$id)->where("statusAppointment", "=", "no")->get();


       
        
        $data = [];
        foreach ($appointments as $app){
            
            $data[] = [

            "appointment_id"=>$app->appointment_id,
            "doctor_id"=>$app->doctor_id,
            "patient_id" => $app->patient_id,
            "symptoms"=> $app->symptoms,
            "appointmentDate"=>$app->appointmentDate,
            "appointmentTime" => $app->appointmentTime,
            "price"=> $app->price,
            "statusAppointment"=>$app->statusAppointment,
            "created_at"=> $app->created_at,
            "updated_at" => $app->updated_at,
            "doctorName"=>$app->doctor->fullName
            ];

                
            }
            
        return response()->json([
            "status"=>"success",
            "appointment"=>$data],200);

          }


    public function appointmentSuccess(Request $request ,$id)
    {
        $appointment = Appointments::where('appointment_id', $id)
            ->first(['appointment_id', 'appointmentDate', 'appointmentTime', 'doctor_id', 'price']);

        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }

        return response()->json([
            'appointment_id' => $appointment->appointment_id,
            'date' => $appointment->appointmentDate,
            'time' => $appointment->appointmentTime,
            'doctor_id' => $appointment->doctor_id,
            'price' => $appointment->price,
        ]);
    }


    public function getUpcomingAppointmentByDoctorId($id)
    {
        $appointments = DB::table('appointments as a')
            ->join('patients as p', 'a.patient_id', '=', 'p.patient_id')
            ->select('a.appointmentDate', 'a.appointmentTime', 'p.fullName as patient_name')
            ->where('a.doctor_id', $id)
            ->where(function ($query) {
                $query->where('a.appointmentDate', '>', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('a.appointmentDate', now()->toDateString())
                            ->where('a.appointmentTime', '>=', now()->toTimeString());
                    });
            })
            ->orderBy('a.appointmentDate')
            ->orderBy('a.appointmentTime')
            ->get();


        return response()->json([
            'status' => 200,
            'data' => $appointments
        ], 200);
    }


    public function getClosedAppointment($id)
    {
        $appointments = DB::table('appointments as a')
            ->join('patients as p', 'a.patient_id', '=', 'p.patient_id')
            ->select('a.appointmentDate', 'a.appointmentTime', 'p.fullName as patient_name')
            ->where('a.doctor_id', $id)
            ->where(function ($query) {
                $query->where('a.appointmentDate', '<', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('a.appointmentDate', now()->toDateString())
                            ->where('a.appointmentTime', '<', now()->toTimeString());
                    });
            })
            ->orderBy('a.appointmentDate', 'desc')
            ->orderBy('a.appointmentTime', 'desc')
            ->get();


        return response()->json([
            'status' => 200,
            'data' => $appointments
        ], 200);
    }


    public function getUpcomingAppointmentByPatientId($id)
    {
        $appointments = DB::table('appointments as a')
            ->join('doctors as d', 'a.doctor_id', '=', 'd.doctor_id')
            ->select('a.appointmentDate', 'a.appointmentTime', 'd.fullName as doctor_name')
            ->where('a.patient_id', $id)
            ->where(function ($query) {
                $query->where('a.appointmentDate', '>', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('a.appointmentDate', now()->toDateString())
                            ->where('a.appointmentTime', '>=', now()->toTimeString());
                    });
            })
            ->orderBy('a.appointmentDate')
            ->orderBy('a.appointmentTime')
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $appointments
        ], 200);
    }


    
}
