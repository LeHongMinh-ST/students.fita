<?php

namespace App\Repositories\Student;

use App\Repositories\Base\BaseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

interface StudentRepositoryInterface extends BaseRepositoryInterface
{
    public function getStudents(array $data);
}