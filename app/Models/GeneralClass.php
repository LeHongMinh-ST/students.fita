<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GeneralClass extends Model
{
    use HasFactory;

    protected $table = 'general_classes';

    protected $fillable = [
        'name',
        'class_code',
        'teacher_id',
        'department_id'
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
