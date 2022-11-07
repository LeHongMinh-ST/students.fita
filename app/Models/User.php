<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'user_name',
        'email',
        'password',
        'created_by',
        'updated_by',
        'role_id',
        'department_id',
        'is_super_admin',
        'is_teacher',
        'teacher_code',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function socials(): MorphMany
    {
        return $this->morphMany(Social::class,'socialable');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function createBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'created_by');
    }

    public function updateBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'updated_by');
    }

    public function approvedReports(): HasMany
    {
        return $this->hasMany(Report::class, 'approved_by');
    }
}
