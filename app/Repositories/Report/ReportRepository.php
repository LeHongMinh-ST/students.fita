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

        if (isset($data['subject'])) {
            $query->where('subject', $data['subject']);
        }

        $user = auth()->user();

        if (@$user->teacher_id && !@$user->is_super_admin) {
            $query->where('teacher_id', $user->id);
        }

        $sort = @$data['sort'] ?? 'DESC';
        $query->orderBy('created_at',$sort );

        if (isset($data['page'])) {
            return $query->with($relationships)->paginate($paginate);
        }

        return $query->with($relationships)->get();
    }
}