<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Family extends Model
{
    use HasFactory;

    protected $table = 'families';

    protected $fillable = [
        'relationship',
        'full_name',
        'job',
        'phone',
        'student_id',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
