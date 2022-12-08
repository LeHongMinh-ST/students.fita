<?php

namespace App\Repositories\Report;

use App\Repositories\Base\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReportRepositoryInterface extends BaseRepositoryInterface
{
    public function getReportsPaginate(array $data): LengthAwarePaginator;
}
