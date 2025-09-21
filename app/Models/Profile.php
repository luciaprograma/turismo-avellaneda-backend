<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'dni',
        'birth_date',
        'address',
        'phone_number',
        'emergency_contact',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
