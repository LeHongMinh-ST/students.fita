<?php

namespace App\Repositories\Student;

use App\Models\Student;
use App\Repositories\Base\BaseRepository;

class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }
}
