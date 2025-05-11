<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //

    protected $primaryKey = "payment_id";

    protected $fillable = [
        "statusPayment",
        "appointment_id",
        'paymentDate',
    ];
    public function appointment()
    {
        return $this->belongsTo(Appointments::class, "appointment_id", "appointment_id");
    }
}
