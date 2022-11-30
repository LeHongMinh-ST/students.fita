<?php

namespace App\Models;

use App\Enums\Student\StudentGender;
use App\Enums\Student\StudentRole;
use App\Enums\Student\StudentSocialPolicyObject;
use App\Enums\Student\StudentStatus;
use App\Enums\Student\StudentTrainingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Student extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'students';

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
        'email_edu',
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
    ];

    protected $hidden = [
        'password',
    ];


    public function socials(): MorphMany
    {
        return $this->morphMany(Social::class, 'socialable');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    public function studentTemps(): HasMany
    {
        return $this->hasMany(StudentTemp::class);
    }

    public function generalClass(): BelongsTo
    {
        return $this->belongsTo(GeneralClass::class, 'class_id');
    }

    public function learningOutcomes(): HasMany
    {
        return $this->hasMany(LearningOutcome::class)->with(['detail']);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'student_id', 'id');
    }

    public function createdReports(): HasMany
    {
        return $this->hasMany(Report::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Student $student) {
            $student->reports()->delete();
            $student->families()->delete();
            $student->socials()->delete();
            $student->createdReports()->delete();
        });
    }

    public function getThumbnailUrlAttribute(): string
    {
        return asset("/storage/{$this->thumbnail}");
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

    protected $appends = [
        'thumbnail_url',
        'role_text',
        'status_text',
        'social_policy_object_text',
        'gender_text',
        'training_text',
    ];
}
