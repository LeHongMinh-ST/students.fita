<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'department_code'
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function generalClasses(): HasMany
    {
        return $this->hasMany(GeneralClass::class);
    }
}
