<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class messages extends Model
{
    //
    protected $primaryKey = "message_id";

    public function sentBy(){
        return $this->belongsTo(User::class, "user_id","sender_id");
    }
    public function recivedBy()
    {
        return $this->belongsTo(User::class, "user_id", "receiver_id");
    }
}
