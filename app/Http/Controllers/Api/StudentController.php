<?php

namespace App\Http\Controllers\Api;

use App\Enums\Student\StudentTempStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoretStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Imports\StudentImport;
use App\Models\StudentTemp;
use App\Repositories\Student\StudentRepositoryInterface;
use App\Repositories\Student\StudentTempRepositoryInterface;
use App\Services\CrawlDataLearningOutcomeService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private StudentRepositoryInterface     $studentRepository,
        private StudentTempRepositoryInterface $studentTempRepository
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
            $condition[] = ['full_name', 'like', '%' . $data['q'] . '%'];
            $orCondition = [
                ['student_code', 'like', '%' . $data['q'] . '%'],
                ['email', 'like', '%' . $data['q'] . '%'],
                ['email_edu', 'like', '%' . $data['q'] . '%'],
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
            $data['password'] = $data['dob'];

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

    public function createStudentTemp(UpdateStudentRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $auth = auth('students')->user();

            $data = $request->all();
            $student = $this->studentRepository->findById($auth->id);

            if ($request->hasFile('image')) {
                $path = Storage::disk('public')->putFile('images/students/thumbnail', $request->file('image'));
                $data['thumbnail'] = $path;
            }

            $student?->fill(array_merge($data, [
                'updated_by' => auth()->id(),
            ]));

            $studentTemp = $this->studentRepository->getFirstBy([
                'student_id' => $auth->id,
                'status_approved' => StudentTempStatus::Pending
            ]);

            if ($studentTemp) {
                $studentTemp->fill(array_merge($data, [
                    'updated_by' => auth()->id(),
                ]));
            } else {
                $dataStudent = array_merge($student->toArray(), [
                    'student_id' => $auth->id,
                    'status_approved' => StudentTempStatus::Pending
                ]);
                $studentTemp = $this->studentRepository->create($dataStudent);
            }

            if (!empty($data['families'])) {
                foreach ($data['families'] as $family) {
                    $studentTemp->families()->updateOrCreate([
                        'family_id' => $family['id'],
                    ], array_merge($family));
                }
            }

            DB::commit();
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error update student ', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function updateStudentByStudentTemp($id)
    {
        try {
            $studentTemp = $this->studentRepository->getFirstBy(['student_id' => $id,]);
            $studentTemp = $this->handleUpdateStudentByStudentTemp($studentTemp);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error update update Student By StudentTemp ', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    private function handleUpdateStudentByStudentTemp($studentTemp)
    {

        switch ($studentTemp->status_approved) {
            case StudentTempStatus::Pending:
                $studentTemp->status_approved = StudentTempStatus::ClassMonitorApproved;
                break;
            case StudentTempStatus::ClassMonitorApproved:
                $studentTemp->status_approved = StudentTempStatus::TeacherApproved;
                break;
            case StudentTempStatus::TeacherApproved:
                $studentTemp->status_approved = StudentTempStatus::Approved;
                $familyTemp = $studentTemp->families;
                $student = $this->studentRepository->getFirstBy(['id' => $studentTemp->student_id]);

                $data = array_intersect_key($studentTemp->toArray(), array_flip(StudentTemp::ONLY_KEY_UPDATE));

                $student?->fill($data);

                $this->studentRepository->createOrUpdate($data);
                if (!empty($familyTemp)) {
                    foreach ($familyTemp as $family) {
                        $student->families()->updateOrCreate(['id' => $family['family_id']], $family);
                    }
                }
                break;
            case StudentTempStatus::Approved:
                break;
        }

        return $studentTemp;
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

    public function importStudentToClass(Request $request, $classId): JsonResponse
    {
        DB::beginTransaction();
        try {
            Excel::import(new StudentImport((int)$classId), $request->file('excel')->store('files'));
            DB::commit();
            return $this->responseSuccess();
        }catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error update learning outcome student', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }

    }
}
