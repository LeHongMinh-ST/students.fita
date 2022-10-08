<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningOutcomeDetail extends Model
{
    use HasFactory;

    protected $table = 'learning_outcome_details';

    protected $fillable = [
        'order',
        'subject_code',
        'subject_name',
        'subject_name',
        'credits',
        'percent_test',
        'percent_exam',
        'diligence_point',
        'progress_point',
        'exam_point',
        'total_point_number',
        'total_point_string',
        'learning_outcomes_id'
    ];

    public function learningOutcome(): BelongsTo
    {
        return $this->belongsTo(LearningOutcome::class);
    }
}
