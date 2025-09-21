<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\VerifyEmailFrontend;


class User extends Authenticatable implements MustVerifyEmail
{
     use HasApiTokens, HasFactory, Notifiable;
   


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
     public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailFrontend());
    }

     public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordFrontend($token));
    }

}
