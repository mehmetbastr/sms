<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'blood_group',
        'email',
        'class',
        'phone_number',
        // 'parent_phone_number',
        // 'student_number',
        // 'parent_number',
        // 'school',
        // 'educations',
        // 'events',
        // 'lessons',        
        // 'status',
        // 'upload'
    ];

    public function getAvatarPath()
    {
        return $this->upload;
    }

        protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $currentMonth = date('m');
            $currentYearLastTwoDigits = substr(date('Y'), -2);

            $getStudent = self::orderBy('student_number', 'desc')->first();

            if ($getStudent) {
                $latestID = intval(substr($getStudent->student_number, 6)); // Son iki hanelerden başlayarak sayıyı al
                $nextID = $latestID + 1;
            } else {
                $nextID = 1;
            }

            $model->student_number = $currentMonth . $currentYearLastTwoDigits . sprintf("%04s", $nextID); // Örnek format: 0424 (Ay: 04, Yıl: 2024)
            
            while (self::where('student_number', $model->student_number)->exists()) {
                $nextID++;
                $model->student_number = $currentMonth . $currentYearLastTwoDigits . sprintf("%04s", $nextID);
            }
        });
    }
}
