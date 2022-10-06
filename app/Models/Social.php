<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Social extends Model
{
    use HasFactory;

    protected $table = [
        'social_id',
        'social_provider',
        'socialable_id',
        'socialable_type',
        'email',
    ];

    public function socialable()
    {
        return $this->morphTo();
    }

}
