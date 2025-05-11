<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    //

    protected $primaryKey = "call_id";

    protected $fillable = [
        "status" ,
        "appointment_id",
        'startedAt',
        "endedAt",
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointments::class, "appointment_id", "appointment_id");
    }
}
