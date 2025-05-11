<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


//Models
use App\Models\Doctors;
use App\Models\Patients;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = "user_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
      
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // protected function casts(): array
    // {
    //     return [
    //        'password' => 'hashed',
    //     ];
    // }


    public function doctor(){
        return $this->hasOne(Doctors::class,"doctor_id","user_id");
    }
   
    public function patient()
    {
        return $this->hasOne(Patients::class, "patient_id", "user_id");
    }

    public function sentMessage()
    {
       return  $this->hasMany(messages::class,"sender_id", "user_id");
    }

    public function recivedMessage()
    {
        return $this->hasMany(messages::class,"receiver_id", "user_id");
    }



    //jwt
    public function getJWTIdentifier()
    {
        return (string)$this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
