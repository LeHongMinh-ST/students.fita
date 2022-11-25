<?php

namespace App\Http\Controllers\Api;

use App\Enums\Report\ReportStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Requests\Report\UpdateReportRequest;
use App\Repositories\Report\ReportRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private ReportRepositoryInterface $reportRepository
    )
    {
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();

        $relationships = ['generalClass', 'families', 'learningOutcomes', 'reports'];
        $columns = ['*'];
        $paginate = $data['limit'] ?? config('constants.limit_of_paginate', 10);
        $condition = [];

        if (isset($data['q'])) {
            $condition[] = ['title', 'like', '%' . $data['q'] . '%'];
        }

        if (isset($data['status'])) {
            $condition[] = ['status', '=', $data['status']];
        }

        if (isset($data['subject'])) {
            $condition[] = ['subject', '=', $data['subject']];
        }

        $reports = $this->reportRepository->getListPaginateBy($condition, $relationships, $columns, $paginate);

        return $this->responseSuccess(['reports' => $reports]);
    }

    public function show($id): JsonResponse
    {
        $report = $this->reportRepository->getFirstBy(['id' => $id]);
        return $this->responseSuccess(['report' => $report]);
    }

    public function store(StoreReportRequest $request): JsonResponse
    {
        try {
            $data = $request->all();
            $department = $this->reportRepository->create(array_merge($data, [
                'created_by' => auth()->id()
            ]));
            return $this->responseSuccess(['department' => $department]);

        } catch (\Exception $exception) {
            Log::error('Error store report', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateReportRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->all();
            $department = $this->reportRepository->findById($id);

            if ($department->status != ReportStatus::Pending) {
                return $this->responseError('Không thể chỉnh sửa phản ánh', [], 400);
            }

            $department?->fill($data);
            $this->reportRepository->createOrUpdate($department);

            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error update report', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function destroy($id): JsonResponse
    {
        try {

            $department = $this->reportRepository->findById($id);

            if ($department->status == ReportStatus::Approved) {
                return $this->responseError('Không thể xóa phản ánh', [], 400);
            }

            $this->reportRepository->deleteById($id);

            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete department', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

}
