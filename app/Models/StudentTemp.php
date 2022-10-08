<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentTemp extends Model
{
    protected $table = 'student_temps';

    protected $fillable = [
        'user_name',
        'full_name',
        'student_code',
        'password',
        'gender',
        'permanent_residence',
        'class_id',
        'major',
        'dob',
        'pob',
        'address',
        'countryside',
        'training_type',
        'school_year',
        'email',
        'phone',
        'nationality',
        'citizen_identification',
        'ethnic',
        'religion',
        'academic_level',
        'thumbnail',
        'social_policy_object',
        'note',
        'status',
        'role',
        'status_approved',
        'student_approved',
        'teacher_approved',
        'admin_approved',
    ];

    public function students(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
