<?php

namespace App\Models;

use App\Enums\Student\StudentGender;
use App\Enums\Student\StudentRole;
use App\Enums\Student\StudentSocialPolicyObject;
use App\Enums\Student\StudentStatus;
use App\Enums\Student\StudentTempStatus;
use App\Enums\Student\StudentTrainingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

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
        'reject_note',
        'change_column',
        'email_edu',
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
        return $this->belongsTo(User::class, 'teacher_approved');
    }

    public function adminApproved(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_approved');
    }

    public function rejectable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'rejectable_type','rejectable_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function (StudentTemp $student) {
            $student->families()->delete();
        });
    }

    public function getThumbnailUrlAttribute(): string
    {
        return Storage::disk('google')->url($this->thumbnail);
    }

    public function getSocialPolicyObjectTextAttribute(): string
    {
        return StudentSocialPolicyObject::getDescription($this->social_policy_object);
    }

    public function getRoleTextAttribute(): string
    {
        return StudentRole::getDescription($this->role);
    }

    public function getStatusTextAttribute(): string
    {
        return StudentStatus::getDescription($this->status);
    }

    public function getGenderTextAttribute(): string
    {
        return StudentGender::getDescription($this->gender);
    }

    public function getTrainingTextAttribute(): string
    {
        return StudentTrainingType::getDescription($this->training_type);
    }

    public function getStatusApprovedTextAttribute(): string
    {
        return @$this->status_approved ? StudentTempStatus::getDescription($this->status_approved) : '';
    }

    public function getRejectRoleTextAttribute(): string
    {
        if ($this->rejectable_type == User::class) {
            if ($this->rejectable->is_teacher) {
                return 'Giảng viên';
            }
            return 'Ban chủ nhiệm';
        }

        return 'Lớp trưởng';
    }

    protected $appends = [
        'status_approved_text',
        'thumbnail_url',
        'role_text',
        'status_text',
        'social_policy_object_text',
        'gender_text',
        'training_text',
        'reject_role_text'
    ];

    protected $casts = [
        'change_column' => 'array',
    ];
}
