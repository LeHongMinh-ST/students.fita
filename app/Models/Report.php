<?php

namespace App\Models;

use App\Enums\Report\ReportStatus;
use App\Enums\Report\ReportSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'student_id',
        'title',
        'subjects',
        'content',
        'class_id',
        'status',
        'status_approve',
        'created_by',
        'approved_by',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusTextAttribute(): string
    {
        return ReportStatus::getDescription($this->status);
    }

    public function getSubjectTextAttribute():string
    {
        return ReportSubject::getDescription($this->subject);

    }

    protected $appends = [
        'status_text',
        'subject_text',
    ];

}
