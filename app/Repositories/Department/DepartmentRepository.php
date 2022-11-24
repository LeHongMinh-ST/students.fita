<?php

namespace App\Repositories\Department;

use App\Models\Department;
use App\Repositories\Base\BaseRepository;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    public function __construct(Department $model)
    {
        parent::__construct($model);
    }

}