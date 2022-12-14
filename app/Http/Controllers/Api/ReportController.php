<?php

namespace App\Http\Controllers\Api;

use App\Enums\Report\ReportStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ChangeReportStatusMultipleRequest;
use App\Http\Requests\Report\ChangeReportStatusRequest;
use App\Http\Requests\Report\DeleteReportMultipleRequest;
use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Requests\Report\UpdateReportRequest;
use App\Repositories\Report\ReportRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $report = $this->reportRepository->findById($id);

            if (auth('students')->check()) {
                $student = auth('students')->user();
                if ($student->id != $report->created_by) {
                    return $this->responseError('Bạn không có quyền thực hiện chức năng này', [], 403);
                }
            }

            if (auth('api')->check()) {
                $auth = auth('api')->user();
                if (@$auth->is_teacher && !@$auth->is_super_admin) {

                    $classIds = $auth?->generalClass?->pluck('id')?->toArray();

                    if (!in_array($report->class_id, $classIds)) {
                        return $this->responseError('Bạn không có quyền thực hiện chức năng này', [], 403);
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
                if ($student->id != $report->created_by) {
                    return $this->responseError('Bạn không có quyền', [], 403);
                }

                if ($report->status == ReportStatus::Approved) {
                    return $this->responseError('Không thể xóa phản ánh', [], 400);
                }
            }

            if (auth('api')->check()) {
                $auth = auth('api')->user();

                if (@$auth->is_teacher && !@$auth->is_super_admin) {

                    $classIds = $auth?->generalClass?->pluck('id')?->toArray();

                    if (!in_array($report->class_id, $classIds)) {
                        return $this->responseError('Bạn không có quyền', [], 403);
                    }
                }
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

    public function deleteSelected(DeleteReportMultipleRequest $request): JsonResponse
    {
        try {
            $reportIds = $request->input('report_ids', []);

            $reports = $this->reportRepository->getByWhereIn('id', $reportIds);

            $arrayClass = $reports->pluck('class_id')->toArray();

            if (auth('api')->check()) {
                $auth = auth('api')->user();
                if (@$auth->is_teacher && !@$auth->is_super_admin) {
                    $classIds = $auth?->generalClass?->pluck('id')?->toArray();
                    if (!array_diff($arrayClass, $classIds)) {
                        return $this->responseError('Bạn không có quyền thực hiện chức năng này', [], 403);
                    }
                }
            }

            $condition[] = ['id', 'in', $reportIds];
            $this->reportRepository->deleteBy($condition);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete select report', [
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
            if (@$user->is_teacher && !@$user->is_super_admin) {
                $classIds = $user?->generalClass?->pluck('id')?->toArray();
                $queryReport->whereIn('class_id', $classIds);
            }
            $queryReport->where('status', ReportStatus::Pending);
        }

        return $this->responseSuccess([
            'reportCount' => $queryReport->count()
        ]);
    }

    public function changeStatusReport(ChangeReportStatusRequest $request, $id): JsonResponse
    {
        try {
            $status = $request->get('status', ReportStatus::Pending);
            $report = $this->reportRepository->findById($id);

            if (auth('api')->check()) {
                $auth = auth('api')->user();
                if (@$auth->is_teacher && !@$auth->is_super_admin) {
                    $classIds = $auth?->generalClass?->pluck('id')?->toArray();
                    if (!in_array($report->class_id, $classIds)) {
                        return $this->responseError('Bạn không có quyền thực hiện chức năng này', [], 403);
                    }
                }
            }

            if ($report->status == ReportStatus::Approved) {
                return $this->responseError('Không thể cập nhật trạng thái', [], 400);
            }

            $data['status'] = $status;

            if ($status == ReportStatus::Approved) {
                $data['approved'] = auth('api')->id();
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

    public function changeStatusMultipleReport(ChangeReportStatusMultipleRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $status = $request->get('status', ReportStatus::Pending);
            $reportIds = $request->get('report_ids', []);
            $model = $this->reportRepository->getModel();
            $model->query()->whereIn('id', $reportIds)->update([
                'status' => $status,
                'approved_by' => auth()->id()
            ]);

            DB::commit();
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error update report', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

}
