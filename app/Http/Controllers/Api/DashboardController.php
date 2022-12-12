<?php

namespace App\Http\Controllers\Api;

use App\Enums\Report\ReportStatus;
use App\Enums\Student\StudentTempStatus;
use App\Http\Controllers\Controller;
use App\Repositories\GeneralClass\GeneralClassRepository;
use App\Repositories\Report\ReportRepositoryInterface;
use App\Repositories\Student\StudentRepositoryInterface;
use App\Repositories\Student\StudentTempRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private StudentRepositoryInterface     $studentRepository,
        private UserRepositoryInterface        $userRepository,
        private ReportRepositoryInterface      $reportRepository,
        private GeneralClassRepository         $generalClassRepository,
        private StudentTempRepositoryInterface $studentTempRepository
    )
    {
    }

    public function index(): JsonResponse
    {
        $studentCount = $this->studentRepository->count();
        $teacherCount = $this->userRepository->count(['is_teacher' => 1]);
        $reportCount = $this->reportRepository->count();
        $classCount = $this->generalClassRepository->count();

        $modelReport = $this->reportRepository->getModel();
        $modelRequest = $this->studentTempRepository->getModel();
        $queryReport = $modelReport->query()->where('status', ReportStatus::Pending);
        $queryRequest = $modelRequest->query()->where('status_approved', StudentTempStatus::ClassMonitorApproved);

        if (auth('api')->check()) {
            $user = auth('api')->user();
            if (@$user->teacher_id && !@$user->is_super_admin) {
                $classIds = $user?->generalClass?->pluck('id')?->toArray();
                $queryReport->whereIn('class_id', $classIds);
                $queryRequest->whereIn('class_id', $classIds);
            }
        }

        $reports = $queryReport->with(['student', 'createdBy', 'approvedBy'])
            ->orderBy('created_at', 'desc')->limit(10)->get();
        $requests = $queryRequest->with(['studentApproved', 'teacherApproved', 'adminApproved', 'student'])
            ->orderBy('created_at', 'desc')->limit(10)->get();

        return $this->responseSuccess([
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'reportCount' => $reportCount,
            'classCount' => $classCount,
            'report' => $reports,
            'requests' => $requests,
        ]);
    }
}
