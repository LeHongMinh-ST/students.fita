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

        $reports = $this->reportRepository->getReportsPaginate($data);

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
                'created_by' => auth('students')->id()
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
            $report= $this->reportRepository->findById($id);

            if (auth('students')->check()) {
                $student = auth('students')->user();
                if ($student->id != $report->created_by) {
                    return $this->responseError('Bạn không có quyền', [], 403);
                }
            }

            if (auth('api')->check()) {
                $auth= auth('api')->user();
                if (@$auth->teacher_id && !@$auth->is_super_admin) {

                    $classIds = $auth?->generalClass?->pluck('id')?->toArray();

                    if (!in_array($report->class_id, $classIds)) {
                        return $this->responseError('Bạn không có quyền', [], 403);
                    }
                }
            }

            if ($report->status != ReportStatus::Pending) {
                return $this->responseError('Không thể chỉnh sửa phản ánh', [], 400);
            }

            $report?->fill($data);
            $this->reportRepository->createOrUpdate($report);

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

            $report = $this->reportRepository->findById($id);
            if (auth('students')->check()) {
                $student = auth('students')->user();
                if ($student->id == $report->created_by) {
                    return $this->responseError('Bạn không có quyền', [], 403);
                }
            }

            if (auth('api')->check()) {
                $auth= auth('api')->user();

                if (@$auth->teacher_id && !@$auth->is_super_admin) {

                    $classIds = $auth?->generalClass?->pluck('id')?->toArray();

                    if (!in_array($report->class_id, $classIds)) {
                        return $this->responseError('Bạn không có quyền', [], 403);
                    }
                }
            }
            if ($report->status == ReportStatus::Approved) {
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

    public function getCountReportPending(): JsonResponse
    {
        $modelReport = $this->reportRepository->getModel();
        $queryReport = $modelReport->query()->where('status', ReportStatus::Pending);

        if (auth('api')->check()) {
            $user = auth('api')->user();
            if (@$user->teacher_id && !@$user->is_super_admin) {
                $classIds = $user?->generalClass?->pluck('id')?->toArray();
                $queryReport->whereIn('class_id', $classIds);
            }
            $queryReport->where('status', ReportStatus::Pending);
        }

        return $this->responseSuccess([
            'reportCount' => $queryReport->count()
        ]);
    }

}
