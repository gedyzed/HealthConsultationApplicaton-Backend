<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $primaryKey = "comment_id";

    protected $fillable = [
        'appointment_id',
        "rating",
        "comment"
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointments::class, "appointment_id", "appointment_id");
    }
}
