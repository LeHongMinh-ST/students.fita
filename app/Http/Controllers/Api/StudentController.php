<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Student\StoretStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Jobs\CrawlDataLearningOutcomeJob;
use App\Repositories\Student\StudentRepositoryInterface;
use App\Services\CrawlDataLearningOutcomeService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    use ResponseTrait;

    public function __construct(private StudentRepositoryInterface $studentRepository)
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
            $condition[] = ['full_name', 'like', '%' . $data['q'] . '%'];
            $orCondition = [
                ['student_code', 'like', '%' . $data['q'] . '%'],
                ['email', 'like', '%' . $data['q'] . '%'],
                ['phone', 'like', '%' . $data['q'] . '%']
            ];
            $condition[] = ['full_name', 'or', $orCondition];
        }

        if (isset($data['student_code'])) {
            $condition[] = ['student_code', '=', $data['student_code']];
        }

        if (isset($data['class_id'])) {
            $condition[] = ['class_id', '=', $data['class_id']];
        }

        if (isset($data['status'])) {
            $condition[] = ['status', '=', $data['status']];
        }

        if (isset($data['role'])) {
            $condition[] = ['role', '=', $data['role']];
        }

        $user = $this->studentRepository->getListPaginateBy($condition, $relationships, $columns, $paginate);

        return $this->responseSuccess(['students' => $user]);
    }

    public function show($id): JsonResponse
    {
        $relationships = ['generalClass', 'families', 'learningOutcomes', 'reports'];
        $columns = ['*'];
        return $this->responseSuccess(['student' => $this->studentRepository->getFirstBy(['id'=> $id], $columns, $relationships)]);
    }

    public function store(StoretStudentRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $authId = auth()->id();
            if ($request->hasFile('image')) {
                $path = Storage::disk('public')->putFile('images/students/thumbnail', $request->file('image'));
                $data['thumbnail'] = $path;
            }

            $data['email_edu'] = $data['student_code'] . config('vnua.mail_student');

            $student = $this->studentRepository->create(array_merge($data, [
                'created_by' => $authId,
                'updated_by' => $authId
            ]));

            $this->extractedStudentRelationship($data, $student);
            DB::commit();
            return $this->responseSuccess(['student' => $student->load(['learningOutcomes', 'families'])]);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error store student', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateStudentRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $student = $this->studentRepository->findById($id);

            if ($request->hasFile('image')) {
                $path = Storage::disk('public')->putFile('images/students/thumbnail', $request->file('image'));
                $data['thumbnail'] = $path;
            }

            $data['email_edu'] = $data['student_code'] . config('vnua.mail_student');

            $student?->fill(array_merge($data, [
                'updated_by' => auth()->id(),
            ]));
            $student = $this->studentRepository->createOrUpdate($student);
            $this->extractedStudentRelationship($data, $student);

            DB::commit();
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error update student', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->studentRepository->deleteById($id);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete student', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }


    /**
     * @param array $data
     * @param $student
     * @return void
     */
    private function extractedStudentRelationship(array $data, $student): void
    {
        if (!empty($data['families'])) {
            foreach ($data['families'] as $family) {
                $student->families()->updateOrCreate(['id' => $family['id'] ?? 0], $family);
            }
        }

        if (!empty($data['reports'])) {
            foreach ($data['reports'] as $reports) {
                $student->reports()->updateOrCreate(['id' => $reports['id'] ?? 0], $reports);
            }
        }

        //Lấy dữ liệu học tập
        app(CrawlDataLearningOutcomeService::class)->crawlData($student?->student_code);
    }

    public function updateDataLearningOutcome($studentId): JsonResponse
    {
        try {
            $student = $this->studentRepository->findById($studentId);
            app(CrawlDataLearningOutcomeService::class)->crawlData($student?->student_code);
            return $this->responseSuccess([
                'student' => $student->load(['learningOutcomes'])
            ]);
        } catch (\Exception $exception) {
            Log::error('Error update learning outcome student', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }

    }
}
