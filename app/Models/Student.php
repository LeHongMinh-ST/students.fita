<?php

namespace App\Models;

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

}
