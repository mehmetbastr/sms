<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'teacher_id',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'mobile_number',
        'alternative_number',
        'joining_date',
        'working_status',
        'work_times',
        'qualification',
        'experiences',
        'sertificates',
        'username',
        'address',
        'town',
        'city',
        'country',
    ];

    
}
