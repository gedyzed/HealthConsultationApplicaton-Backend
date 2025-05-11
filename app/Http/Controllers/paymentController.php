<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class paymentController extends Controller
{
    //
    public function setPayment(Request $request){
        $validator = Validator::make($request->all(), [
            "statusPayment" => "nullable",
            "appointment_id" => "required",
            'paymentDate' => 'required',
         
        ]);

        if ($validator->fails()) {
            $data = [
                "status" => "failed",
                "data" => $validator->errors(),
            ];
            return response()->json($data, 422);
        } else {
            $payment = new Payment();
           $payment ->statusPayment = $request->statusPayment;
           $payment ->appointment_id = $request->appointment_id;
           $payment ->paymentDate = $request->paymentDate;
           $payment ->save();
            return response()->json($payment, 200);
        }
    }
}
