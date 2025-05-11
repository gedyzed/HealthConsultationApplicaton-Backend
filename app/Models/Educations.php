<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Educations extends Model
{
    //

    protected $primaryKey = "education_id";
    public function doctor()
    {
        return $this->belongsTo(Doctors::class, "doctor_id", "doctor_id");
    }
}
