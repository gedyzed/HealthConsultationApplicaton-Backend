<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{
    //

    protected $primaryKey = "language_id";
    public function doctor()
    {
        return $this->belongsTo(Doctors::class, "doctor_id", "doctor_id");
    }
}
