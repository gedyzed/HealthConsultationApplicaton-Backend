<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specializations extends Model
{
    //

    protected $primaryKey = "specialization_id";
    public function doctor()
    {
        return $this->belongsTo(Doctors::class, "doctor_id", "doctor_id");
    }
}
