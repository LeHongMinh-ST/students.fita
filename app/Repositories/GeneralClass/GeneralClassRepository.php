<?php

namespace App\Repositories\GeneralClass;

use App\Models\GeneralClass;
use App\Repositories\Base\BaseRepository;

class GeneralClassRepository extends BaseRepository implements GeneralClassRepositoryInterface
{
    public function __construct(GeneralClass $model)
    {
        parent::__construct($model);
    }
}