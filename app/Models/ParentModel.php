<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ParentModel extends Model
{

    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'blood_group',
        'religion',
        'email',
        'class',
        'school',
        'educations',
        'events',
        'lessons',
        'phone_number',
        'parent_phone_number',
        'upload',
        'student_number',
        'parent_number',
        'roll',
        'status'
        
    ];
}
