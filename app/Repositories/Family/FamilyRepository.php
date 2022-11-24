<?php

namespace App\Repositories\Family;

use App\Models\Family;
use App\Repositories\Base\BaseRepository;

class FamilyRepository extends BaseRepository implements FamilyRepositoryInterface
{
    public function __construct(Family $model)
    {
        parent::__construct($model);
    }
}