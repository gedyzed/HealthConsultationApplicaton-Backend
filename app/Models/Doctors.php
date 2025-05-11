<?php

namespace App\Models;

use Doctrine\Inflector\LanguageInflectorFactory;
use Illuminate\Database\Eloquent\Model;


class Doctors extends Model
{
    //


    protected $primaryKey = "doctor_id";

    protected $fillable = [
        'fullName',
        "doctor_id",
        "pricing",
        'image',
        "yearOfExperience",
        "idImage",
        "aboutMe"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "doctor_id");
    }
   

    public function appointments()
    {
        return $this->hasMany(Appointments::class, "doctor_id", "doctor_id");
    }

    public function certificates(){
        return $this->hasMany(Certificates::class,"doctor_id","doctor_id");
    }
    public function languages()
    {
        return $this->hasMany(Languages::class, "doctor_id", "doctor_id");
    }
    public function educations()
    {
        return $this->hasMany(Educations::class, "doctor_id", "doctor_id");
    }
    public function specializations()
    {
        return $this->hasMany(Specializations::class, "doctor_id", "doctor_id");
    }
}
