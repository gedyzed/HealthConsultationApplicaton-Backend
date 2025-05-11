<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    //
    protected $primaryKey = "patient_id";
    
    protected $fillable = [
        "patient_id",
        'fullName' ,
        'image' ,
        "gender" ,
        "idImage" ,
        "aboutMe" 
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "patient_id");
    }
    public function appointments()
    {
        return $this->hasMany(Appointments::class, "patient_id", "patient_id");
    }
}
