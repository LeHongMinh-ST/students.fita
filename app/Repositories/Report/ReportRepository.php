<?php

namespace App\Repositories\Report;

use App\Models\Report;
use App\Repositories\Base\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportRepository extends BaseRepository implements ReportRepositoryInterface
{
    public function __construct(Report $model)
    {
        parent::__construct($model);
    }

    public function getReportsPaginate($data)
    {
        $relationships = ['student', 'createdBy', 'approvedBy'];
        $columns = ['*'];
        $paginate = $data['limit'] ?? config('constants.limit_of_paginate', 10);
        $query = $this->model->query()->select($columns);
        if (isset($data['q'])) {
            $q = $data['q'];
            $query->where('title', 'like', "%{$q}%");
        }

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        if (isset($data['subjects'])) {
            $query->where('subjects', $data['subjects']);
        }

        if (auth('api')->check()) {
            $user = auth('api')->user();

            if (@$user->is_teacher && !@$user->is_super_admin) {
                $classId = $user?->generalClass?->pluck('id')?->toArray();
                $query->whereIn('class_id', $classId);
            }
        }


        if (auth('students')->check()) {
            $student = auth('students')->user();
            $query->where('created_by', $student->id);
        }

        $sort = @$data['sort'] ?? 'DESC';
        $query->orderBy('created_at',$sort );

        if (isset($data['page'])) {
            return $query->with($relationships)->paginate($paginate);
        }

        return $query->with($relationships)->get();
    }
}
