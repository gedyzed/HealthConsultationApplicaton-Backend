<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Call;


class Appointments extends Model
{
    //
    protected $primaryKey = "appointment_id";

    protected $fillable = [
        'doctor_id',
        "patient_id",
        "symptoms",
        'appointmentDate',
        "appointmentTime",
        "price",
        "statusAppointment"
    ];


    public function doctor(){
        return $this->belongsTo(Doctors::class, "doctor_id","doctor_id");
    }

    public function patient()
    {
        return $this->belongsTo(Patients::class, "patient_id", "patient_id");
    }

    public function call(){
        return $this->hasOne(Call::class, "appointment_id", "appointment_id");
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, "appointment_id", "appointment_id");
    }
    public function comment()
    {
        return $this->hasOne(Comments::class, "appointment_id", "appointment_id");
    }
}
