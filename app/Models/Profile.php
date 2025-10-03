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
        'phone_country_code',
        'phone_area_code',
        'phone_number',
        'emergency_country_code',
        'emergency_area_code',
        'emergency_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
