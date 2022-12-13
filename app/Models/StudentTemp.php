<?php

namespace App\Models;

use App\Enums\Student\StudentTempStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'student_id',
        'reject_type',
        'reject_id',
    ];

    const ONLY_KEY_UPDATE = [
        'gender',
        'permanent_residence',
        'major',
        'dob',
        'pob',
        'address',
        'countryside',
        'training_type',
        'email',
        'phone',
        'nationality',
        'citizen_identification',
        'ethnic',
        'religion',
        'academic_level',
        'thumbnail',
        'social_policy_object',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function families(): HasMany
    {
        return $this->hasMany(FamilyTemp::class, 'student_temp_id');
    }

    public function studentApproved(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_approved');
    }

    public function teacherApproved(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'teacher_approved');
    }

    public function adminApproved(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'admin_approved');
    }

    public function rejectable()
    {
        return $this->morphTo(__FUNCTION__, 'reject_type','reject_id');
    }

    public function getStatusApprovedTextAttribute(): string
    {
        return @$this->status_approved ? StudentTempStatus::getDescription($this->status_approved) : '';
    }

    protected $appends = [
        'status_approved_text',
    ];
}
