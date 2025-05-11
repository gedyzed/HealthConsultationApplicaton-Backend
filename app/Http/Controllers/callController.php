<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class callController extends Controller
{
    //

    public function setCall(Request $request){

        $validator = Validator::make($request->all(), [
            "status" => "required",
            "appointment_id" => "required",
            'startedAt' => 'nullable',
            "endedAt" => "nullable",
            
        ]);

        if ($validator->fails()) {
            $data = [
                "status" => "failed",
                "data" => $validator->errors(),
            ];
            return response()->json($data, 422);
        } else {


            $call= new Call();
            $call->status = $request->status;
            $call->appointment_id = $request->appointment_id;
            $call->startedAt = $request->startedAt;
            $call->endedAt = $request->endedAt;
            
            $call->save();
            return response()->json($call, 200);
        }
    }



    
}
