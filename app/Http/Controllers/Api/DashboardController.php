<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\GeneralClass\GeneralClassRepository;
use App\Repositories\Report\ReportRepositoryInterface;
use App\Repositories\Student\StudentRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private StudentRepositoryInterface $studentRepository,
        private UserRepositoryInterface    $userRepository,
        private ReportRepositoryInterface  $reportRepository,
        private GeneralClassRepository     $generalClassRepository
    )
    {
    }

    public function index(): JsonResponse
    {
        $studentCount = $this->studentRepository->count();
        $teacherCount = $this->userRepository->count(['is_teacher' => 1]);
        $reportCount = $this->reportRepository->count();
        $classCount= $this->generalClassRepository->count();

        return $this->responseSuccess([
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            '$reportCount' => $reportCount,
            '$classCount' => $classCount,
        ]);
    }
}
