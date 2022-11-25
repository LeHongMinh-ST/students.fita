<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'department_code',
        'created_by',
        'updated_by',
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function createBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function generalClasses(): HasMany
    {
        return $this->hasMany(GeneralClass::class);
    }
}
