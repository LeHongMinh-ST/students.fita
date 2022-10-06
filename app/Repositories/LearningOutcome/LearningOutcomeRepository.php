<?php

namespace App\Repositories\LearningOutcome;

use App\Models\LearningOutcome;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class LearningOutcomeRepository extends BaseRepository implements LearningOutcomeRepositoryInterface
{
    public function __construct(LearningOutcome $model)
    {
        parent::__construct($model);
    }
}