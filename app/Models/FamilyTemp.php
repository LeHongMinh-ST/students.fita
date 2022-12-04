<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyTemp extends Model
{
    use HasFactory;

    protected $table = 'families';

    protected $fillable = [
        'family_id',
        'relationship',
        'full_name',
        'job',
        'phone',
        'student_temp_id',
        'student_id',
    ];

    public function studentTemp(): BelongsTo
    {
        return $this->belongsTo(StudentTemp::class, 'student_temp_id');
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(Family::class, 'family_id');
    }
}
