<?php

namespace App\Repositories\Student;

use App\Models\StudentTemp;
use App\Repositories\Base\BaseRepository;

class StudentTempRepository extends BaseRepository implements StudentTempRepositoryInterface
{
    public function __construct(StudentTemp $model)
    {
        parent::__construct($model);
    }
}
