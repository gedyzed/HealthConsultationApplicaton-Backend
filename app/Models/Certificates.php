<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificates extends Model
{
    //
    protected $primaryKey = "certificate_id";
    public function doctor(){
        return $this->belongsTo(Doctors::class,"doctor_id","doctor_id");
    }
}
