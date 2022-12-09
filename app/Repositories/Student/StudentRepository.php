<?php

namespace App\Repositories\Student;

use App\Models\Student;
use App\Repositories\Base\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
    public function __construct(Student $model)
    {
        parent::__construct($model);
    }

    public function getStudents(array $data)
    {
        $relationships = ['generalClass', 'families', 'learningOutcomes', 'reports'];
        $columns = ['*'];
        $paginate = $data['limit'] ?? config('constants.limit_of_paginate', 10);
        $condition = [];

        $query = $this->model->query()->select($columns);

        if (isset($data['q'])) {
            $q = $data['q'];

            $query->where('full_name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('email_edu', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%")
                ->orWhere('student_code', 'like', "%{$q}%");
        }

        if (isset($data['student_code'])) {
            $query->where('student_code', $data['student_code']);
        }

        if (isset($data['class_id'])) {
            $query->where('class_id', $data['class_id']);
        }

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        if (isset($data['role'])) {
            $query->where('role', $data['role']);
        }
        if (isset($data['page'])) {
            return $query->with($relationships)->paginate($paginate);
        }

        return $query->with($relationships)->get();
    }
}
