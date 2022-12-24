<?php

namespace App\Http\Controllers\Api;

use App\Enums\Student\StudentRole;
use App\Enums\Student\StudentTempStatus;
use App\Exceptions\PermissionStatusException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ResetMyPasswordRequest;
use App\Http\Requests\Student\DeleteStudentTempRequest;
use App\Http\Requests\Student\ImportStudentRequest;
use App\Http\Requests\Student\ResetPasswordRequest;
use App\Http\Requests\Student\StoretStudentRequest;
use App\Http\Requests\Student\StudentChangeUpdateTempMultiple;
use App\Http\Requests\Student\StudentTempChangeStatusRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Http\Requests\Student\UpdateStudentTempRequest;
use App\Imports\StudentImport;
use App\Models\Student;
use App\Models\StudentTemp;
use App\Models\User;
use App\Repositories\Student\StudentRepositoryInterface;
use App\Repositories\Student\StudentTempRepositoryInterface;
use App\Services\CrawlDataLearningOutcomeService;
use App\Traits\ResponseTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

        $user = $this->studentRepository->getStudents($data);

        return $this->responseSuccess(['students' => $user]);
    }

    public function show($id): JsonResponse
    {
        $relationships = ['generalClass', 'families', 'learningOutcomes', 'reports'];
        $columns = ['*'];
        return $this->responseSuccess(['student' => $this->studentRepository->getFirstBy(['id' => $id], $columns, $relationships)]);
    }

    public function store(StoretStudentRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $authId = auth()->id();

            if ($request->hasFile('image')) {
                $path = Storage::putFile('images/students/thumbnail', $request->file('image'));
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
                $path = Storage::putFile('images/students/thumbnail', $request->file('image'));
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

    public function createStudentTemp(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $auth = auth('students')->user();
            $data = [];
            $dataRequest = $request->all();
            foreach ($dataRequest as $key => $value) {
                if ($key == "id") $data["student_id"] = json_decode($value, true);
                else $data[$key] = json_decode($value, true);
            }
            $student = $this->studentRepository->findById($auth->id);

            if ($request->hasFile('image')) {
                $path = Storage::putFile('images/students/thumbnail', $request->file('image'));
                $data['thumbnail'] = $path;
            }

            $studentTemp = $this->studentTempRepository->getFirstBy([
                'student_id' => $auth->id,
                'status_approved' => StudentTempStatus::Pending
            ]);

            if ($studentTemp) {
                $studentTemp->fill(array_merge($data, [
                    'updated_by' => auth()->id(),
                ]));
                $this->studentTempRepository->createOrUpdate($studentTemp);
            } else {
                $dataStudent = array_merge($data, [
                    'student_id' => $auth->id,
                    'status_approved' => StudentTempStatus::Pending
                ]);

                $studentTemp = $this->studentTempRepository->create($dataStudent);
            }

            $studentTemp = $this->handleChangeColumnRequest($studentTemp, $student);

            if (!empty($data['families'])) {
                $studentTemp->families()->delete();

                foreach ($data['families'] as $family) {
                    $family['student_id'] = $studentTemp->student_id;
                    $studentTemp->families()->create($family);
                }
                if ($this->isChangeFamily($student->families?->toArray(), $studentTemp->families?->toArray())) {
                    $changeColumn = $studentTemp->change_column;
                    $changeColumn[] = 'family';
                    $studentTemp->change_column = $changeColumn;
                }

            }

            $this->studentTempRepository->createOrUpdate($studentTemp);

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

    public function updateStudentByStudentTemp(StudentTempChangeStatusRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $status = $request->get('status', 0);

            $studentTemp = $this->studentTempRepository->getFirstBy(['id' => $id]);

            if (auth('students')->check()) {
                $student = auth('students')->user();
                if ($student->role != StudentRole::ClassMonitor && $studentTemp->student_id != @$student->id) {
                    return $this->responseError('Bạn không có quyền truy cập', [], 403);
                }
            }

            $studentTemp = $this->handleUpdateStudentByStudentTemp($studentTemp, $status);
            $this->studentTempRepository->createOrUpdate($studentTemp);

            DB::commit();
            return $this->responseSuccess();
        } catch (PermissionStatusException $exception) {
            DB::rollBack();
            Log::error('Error change status student', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError($exception->getMessage(), [], 403, 403);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error update update student By StudentTemp ', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function updateStudentByStudentTempMultiple(StudentChangeUpdateTempMultiple $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $ids = $request->get('request_ids', []);
            $status = $request->get('status', 0);
            $rejectNote = $request->get('reject_note', '');

            $studentTemps = $this->studentTempRepository->getByWhereIn('id', $ids);
            foreach ($studentTemps as $studentTemp) {
                if (auth('students')->check()) {
                    $student = auth('students')->user();
                    if ($student->role != StudentRole::ClassMonitor && $studentTemp->student_id != @$student->id) {
                        return $this->responseError('Bạn không có quyền truy cập', [], 403);
                    }
                }

                $studentTemp = $this->handleUpdateStudentByStudentTemp($studentTemp, $status, $rejectNote);
                $this->studentTempRepository->createOrUpdate($studentTemp);
            }

            DB::commit();
            return $this->responseSuccess();
        } catch (PermissionStatusException $exception) {
            DB::rollBack();
            Log::error('Error change status student multiple', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError($exception->getMessage(), [], 403, 403);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error update update student By StudentTemp ', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function deleteRequest($id): JsonResponse
    {
        try {
            $studentTemp = $this->studentTempRepository->getFirstBy(['id' => $id], ['*'], ['student']);
            if (!$studentTemp) {
                return $this->responseError('Không tìm thấy bản ghi', [], 400, 400);
            }

            if (@$studentTemp->status_approved == StudentTempStatus::Approved) {
                return $this->responseError('Yêu cầu đã được duyệt không thể xóa', [], 400, 400);
            }

            if (auth('students')->check()) {
                $student = auth('students')->user();
                if ($student->role != StudentRole::ClassMonitor) {
                    if ($studentTemp->student_id != $student->id) {
                        return $this->responseError('Bạn không có quyền thực hiện chức năng này!', [], 403, 403);
                    }
                }
            }

            if (auth('api')->check()) {
                $auth = auth('api')->user();
                if (@$auth->is_teacher && !@$auth->is_super_admin) {
                    $classIds = $auth?->generalClass?->pluck('id')?->toArray();
                    if (!in_array($studentTemp->student->class_id, $classIds)) {
                        return $this->responseError('Bạn không có quyền thực hiện chức năng này', [], 403);
                    }
                }
            }
            $this->studentTempRepository->deleteById($id);

            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete request student', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function getUpdateRequestPending(): JsonResponse
    {
        $studentTemp = $this->studentTempRepository->getFirstBy([
            'student_id' => auth('students')->id(),
            'status_approved' => StudentTempStatus::Pending
        ]);
        $relationships = ['studentApproved', 'teacherApproved', 'adminApproved', 'student', 'rejectable', 'families', 'generalClass'];

        return $this->responseSuccess([
            'studentTemp' => $studentTemp?->load($relationships)
        ]);
    }

    public function deleteRequestSelected(DeleteStudentTempRequest $request): JsonResponse
    {
        try {
            $requestIds = $request->get('request_ids', []);

            $studentTemps = $this->studentTempRepository->getByWhereIn('id', $requestIds);

            $studentTemps->load('student');

            $arrayClass = $studentTemps->pluck('student.class_id')->toArray();

            if (auth('api')->check()) {
                $auth = auth('api')->user();
                if (@$auth->is_teacher && !@$auth->is_super_admin) {
                    $classIds = $auth?->generalClass?->pluck('id')?->toArray();
                    if (!array_diff($arrayClass, $classIds)) {
                        return $this->responseError('Bạn không có quyền thực hiện chức năng này', [], 403);
                    }
                }
            }

            $requestIds = $studentTemps->filter(function ($item) {
                return $item->status_approved != StudentTempStatus::Approved;
            })->pluck('id')->toArray();

            $condition[] = ['id', 'in', $requestIds];
            $this->studentTempRepository->deleteBy($condition);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete request student', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    /**
     * @throws PermissionStatusException
     */
    private function handleUpdateStudentByStudentTemp($studentTemp, $status, $rejectNote = '')
    {
        $auth = auth('api')->user();
        $student = auth('students')->user();

        switch ($status) {
            case StudentTempStatus::ClassMonitorApproved:
                if (!$student) {
                    throw new PermissionStatusException('Bạn không có quyền thực hiện chức năng này');
                }

                if ($studentTemp->status_approved == StudentTempStatus::Pending) {
                    $studentTemp = $this->extractedApprovedData($studentTemp, @auth('students')->id(), StudentTempStatus::ClassMonitorApproved);
                }
                break;
            case StudentTempStatus::TeacherApproved:
                if (!$auth->is_teacher) {
                    throw new PermissionStatusException('Bạn không có quyền thực hiện chức năng này');
                }

                if ($studentTemp->status_approved == StudentTempStatus::ClassMonitorApproved) {
                    $studentTemp = $this->extractedApprovedData($studentTemp, @auth('api')->id(), StudentTempStatus::TeacherApproved);
                }
                break;
            case StudentTempStatus::Approved:
                if ($auth->is_teacher) {
                    throw new PermissionStatusException('Bạn không có quyền thực hiện chức năng này');
                }

                if ($studentTemp->status_approved == StudentTempStatus::TeacherApproved) {
                    $studentTemp = $this->extractedApprovedData($studentTemp, @auth('api')->id(), StudentTempStatus::Approved);

                    $familyTemp = $studentTemp->families;
                    $student = $this->studentRepository->getFirstBy(['id' => $studentTemp->student_id]);
                    $data = array_intersect_key($studentTemp->toArray(), array_flip(StudentTemp::ONLY_KEY_UPDATE));
                    $student?->fill($data);

                    $this->studentRepository->createOrUpdate($student);
                    if (!empty($familyTemp)) {
                        $student->families()->delete();
                        foreach ($familyTemp as $family) {
                            $student->families()->updateOrCreate(['id' => $family->family_id], $family->toArray());
                        }
                    }
                }
                break;
            case StudentTempStatus::Reject:
                if ($studentTemp->status_approved != StudentTempStatus::Approved) {
                    if (auth('students')->check()) {
                        if ($studentTemp->status_approved == StudentTempStatus::Pending) {
                            $studentTemp = $this->extractedRejectData($studentTemp, $student, Student::class, $rejectNote);
                        }
                    }

                    if (auth('api')->check()) {
                        if ($auth->is_teacher && !$auth->is_super_admin) {
                            if ($studentTemp->status_approved == StudentTempStatus::ClassMonitorApproved) {
                                $studentTemp = $this->extractedRejectData($studentTemp, $auth, User::class, $rejectNote);
                            }
                        }

                        if (!$auth->is_teacher || $auth->is_super_admin) {
                            $studentTemp = $this->extractedRejectData($studentTemp, $auth, User::class, $rejectNote);
                        }

                    }
                }
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
            $student->families()->delete();
            foreach ($data['families'] as $family) {
                $student->families()->updateOrCreate(['id' => $family['id'] ?? 0], $family);
            }
        }

//        if (!empty($data['reports'])) {
//            foreach ($data['reports'] as $reports) {
//                $student->reports()->updateOrCreate(['id' => $reports['id'] ?? 0], $reports);
//            }
//        }

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

    public function resetPassword(ResetPasswordRequest $request, $id): JsonResponse
    {
        try {
            $password = $request->input('password', '');

            $this->studentRepository->updateById($id, [
                'password' => $password,
                'updated_by' => auth()->id()
            ]);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error reset password student', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function resetMyPassword(ResetMyPasswordRequest $request): JsonResponse
    {
        try {
            $student = auth('students')->user();

            if (!Hash::check($request->input('password_old', ''), $student->password)) {
                return $this->responseError('', [
                    'password_old' => ['Mật khẩu cũ không chính xác!']
                ], '400');
            }

            $password = $request->input('password', '');

            $this->studentRepository->updateById($student->id, [
                'password' => $password,
                'updated_by' => auth()->id()
            ]);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error reset my password student', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function importStudentToClass(ImportStudentRequest $request, $classId): JsonResponse
    {
        DB::beginTransaction();
        try {
            if (!$request->hasFile('excel')) {
                return $this->responseError('Error', [
                    'file' => 'Tệp không được để trống'
                ]);
            }

            Excel::import(new StudentImport((int)$classId), $request->file('excel')->store('files'));

            DB::commit();
            return $this->responseSuccess();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $exception) {
            DB::rollBack();

            $errors = $exception->failures();

            Log::error('Error import student excel', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError('Error', ['import_error' => $errors], 400);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error import student', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }

    }

    public function getTemplateImportFile(): BinaryFileResponse
    {
        $file = public_path() . '/template/import_student_template.xlsx';
        return response()->download($file, 'import_student_template.xlsx');
    }

    public function getClass(Request $request): JsonResponse
    {
        $data = $request->all();
        $paginate = @$data['limit'] ?? config('constants.limit_of_paginate', 10);

        $auth = auth('students')->user();
        $class = $auth->generalClass;
        $class->load(['teacher', 'department']);

        if (@$data['q']) $students = $class->students()->where('full_name', 'like', "%{$data['q']}%")->paginate($paginate);
        else $students = $class->students()->paginate($paginate);

        return $this->responseSuccess([
            'class' => $class,
            'students' => $students
        ]);
    }

    public function getRequestUpdateStudent(Request $request): JsonResponse
    {
        $data = $request->all();
        $relationship = ['studentApproved', 'teacherApproved', 'adminApproved', 'student', 'rejectable', 'families'];
        $paginate = $data['limit'] ?? config('constants.limit_of_paginate', 10);
        $model = $this->studentTempRepository->getModel();
        $query = $model->query();

        if (auth('students')->check()) {
            $student = auth('students')->user();
            if ($student->role == StudentRole::ClassMonitor) {
                $query->where('class_id', $student->class_id);
            }
        }

        if (auth('api')->check()) {
            $auth = auth('api')->user();
            if (@$auth->is_teacher && !@$auth->is_super_admin) {
                $classIds = $auth->generalClass->pluck('id')->toArray();
                $query->whereIn('class_id', $classIds);
            }
        }

        if (@$data['q']) {
            $text = $data['q'];
            $query->where('student_code', 'like', '%' . $text . '%')
                ->orWhereHas('student', function ($q) use ($text) {
                    return $q->where('full_name', 'like', '%' . $text . '%');
                });
        }

        if (@$data['status_approved']) {
            $query->where('status_approved', $data['status_approved']);
        }

        if (@$data['teacher_approved']) {
            $query->where('teacher_approved', $data['teacher_approved']);
        }

        if (@$data['admin_approved']) {
            $query->where('admin_approved', $data['admin_approved']);
        }

        $requests = $query->with($relationship)->orderByDesc('created_at')->get();
        if (@$data['page'])
            $requests = $query->with($relationship)->orderByDesc('created_at')->paginate($paginate);

        return $this->responseSuccess([
            'requests' => $requests
        ]);
    }

    public function showRequestUpdateStudent($id): JsonResponse
    {
        $relationship = ['studentApproved', 'teacherApproved', 'adminApproved', 'student', 'rejectable', 'families', 'generalClass'];
        $request = $this->studentTempRepository->getFirstBy(['id' => $id], ['*'], $relationship);

        return $this->responseSuccess([
            'request' => $request
        ]);
    }

    public function getMyRequestUpdateStudent(Request $request): JsonResponse
    {
        $data = $request->all();
        $paginate = $data['limit'] ?? config('constants.limit_of_paginate', 10);
        $model = $this->studentTempRepository->getModel();
        $query = $model->query();

        if (auth('students')->check()) {
            $student = auth('students')->user();
            $query->where('student_id', $student->id);
        }

        $relationships = ['studentApproved', 'teacherApproved', 'adminApproved', 'student', 'rejectable', 'families'];

        $requests = $query->with($relationships)->orderBy('created_at', 'desc')->get();
        if (@$data['page'])
            $requests = $query->with($relationships)->orderBy('created_at', 'desc')->paginate($paginate);

        return $this->responseSuccess([
            'requests' => $requests
        ]);
    }

    public function getCountRequest(): JsonResponse
    {
        $modelRequest = $this->studentTempRepository->getModel();
        $queryRequest = $modelRequest->query();

        if (auth('api')->check()) {
            $user = auth('api')->user();
            if (@$user->is_teacher && !@$user->is_super_admin) {
                $classIds = $user?->generalClass?->pluck('id')?->toArray();
                $queryRequest->where('status_approved', StudentTempStatus::ClassMonitorApproved)->whereIn('class_id', $classIds);
            }

            if (!@$user->is_teacher || @$user->is_super_admin) {
                $queryRequest->where('status_approved', StudentTempStatus::TeacherApproved);
            }
        }

        return $this->responseSuccess([
            'requestCount' => $queryRequest->count()
        ]);
    }

    public function getStudentClassMonitor(): JsonResponse
    {
        $student = auth('students')->user();
        if ($student->role != StudentRole::ClassMonitor) {
            return $this->responseError('Bạn không có quyền thực hiện chức năng này', [], 403);
        }

        $classId = $student->class_id;

        $students = $this->studentRepository->allBy(['class_id' => $classId]);
        return $this->responseSuccess(['students' => $students]);
    }

    /**
     * @param $studentTemp
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $auth
     * @param $type
     * @param mixed $rejectNote
     */
    private function extractedRejectData($studentTemp, ?Authenticatable $auth, $type, mixed $rejectNote)
    {
        $studentTemp->status_approved = StudentTempStatus::Reject;
        $studentTemp->rejectable_type = $type;
        $studentTemp->rejectable_id = $auth->id;
        $studentTemp->reject_note = $rejectNote;
        return $studentTemp;
    }

    /**
     * @param $studentTemp
     * @param $idApproved
     * @param $status
     */
    private function extractedApprovedData($studentTemp, $idApproved, $status)
    {
        $studentTemp->status_approved = $status;
        $studentTemp->admin_approved = $idApproved;
        return $studentTemp;
    }

    private function handleChangeColumnRequest($studentTemp, $student)
    {
        $arrayChangeColumn = [];

        foreach ($student->toArray() as $key => $value) {
            if ($key == 'id' || $key == 'created_at' || $key == 'updated_at') continue;
            if ($studentTemp->$key != $value) {
                $arrayChangeColumn[] = $key;
            }
        }
        $studentTemp->change_column = $arrayChangeColumn;
        return $studentTemp;
    }

    private function isChangeFamily($oldFamily, $newFamily): int|array
    {
        try {
            $oldFamily = collect($oldFamily)->map(function ($family) {
                return Arr::only($family, ['relationship', 'full_name', 'job', 'phone', 'student_id',]);
            })->toArray();
            $newFamily = collect($newFamily)->map(function ($family) {
                return Arr::only($family, ['relationship', 'full_name', 'job', 'phone', 'student_id',]);
            })->toArray();

            if (empty($oldFamily) && !empty($newFamily)) {
                return true;
            }

            foreach ($oldFamily as $key => $value) {
                if (is_array($value)) {

                    if (!isset($newFamily[$key])) {
                        $difference[$key] = $value;

                    } elseif (!is_array($newFamily[$key])) {
                        $difference[$key] = $value;

                    } else {
                        $newDiff = $this->isChangeFamily($value, $newFamily[$key]);
                        if ($newDiff != FALSE) {
                            $difference[$key] = $newDiff;
                        }
                    }

                } elseif (!isset($newFamily[$key]) || $newFamily[$key] != $value) {
                    $difference[$key] = $value;
                }
            }
            return !empty($difference);
        }catch (\Exception $exception) {
            throw new \Exception($exception);
        }
    }
}
