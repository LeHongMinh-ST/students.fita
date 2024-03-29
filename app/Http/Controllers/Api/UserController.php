<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ResetMyPasswordRequest;
use App\Http\Requests\Role\DeleteRoleRequest;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use ResponseTrait;

    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function index(Request $request): JsonResponse
    {

        $data = $request->all();
        $relationships = ['role', 'department'];
        $columns = ['*'];
        $paginate = $data['limit'] ?? config('constants.limit_of_paginate', 10);
        $condition = [];

        if (isset($data['q'])) {
            $condition[] = ['user_name', 'like', '%' . $data['q'] . '%'];
            $orCondition = [
                ['full_name', 'like', '%' . $data['q'] . '%'],
                ['email', 'like', '%' . $data['q'] . '%'],
                ['phone', 'like', '%' . $data['q'] . '%']
            ];
            $condition[] = ['user_name', 'or', $orCondition];
        }

        if (!empty($data['is_teacher']) && $data['is_teacher'] == 1) {
            $condition[] = ['is_teacher', '=', 1];
        }

        $sort = @$data['sort'] ?? 'DESC';
        $condition[] = ['created_at', 'ORDER_BY', $sort];

        $users = $this->userRepository->allBy($condition, $relationships, $columns);

        if (isset($data['page'])) {
            $users = $this->userRepository->getListPaginateBy($condition, $relationships, $columns, $paginate);
        }

        return $this->responseSuccess(['users' => $users]);
    }

    public function show($id): JsonResponse
    {
        $relationships = ['role', 'department'];
        $columns = ['*'];

        return $this->responseSuccess([
            'user' => $this->userRepository->findById($id, $columns, $relationships)
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $authId = auth()->id();
            $user = $this->userRepository->create(array_merge($data, [
                'created_by' => $authId,
                'updated_by' => $authId
            ]));
            DB::commit();
            return $this->responseSuccess(['user' => $user]);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error store user', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $path = Storage::disk('public')->putFile('images/user/thumbnail', $request->file('image'));
                $data['thumbnail'] = $path;
            }
            $this->userRepository->updateById($id, array_merge($data, [
                'updated_by' => auth()->id()
            ]));
            DB::commit();
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error update user', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function destroy($id): JsonResponse
    {
        try {

            $auth = auth('api')->user();
            if ($id == $auth->id) {
                return $this->responseError('Không thể xóa người dùng này!');
            }

            $this->userRepository->deleteById($id);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete user', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function deleteSelected(DeleteUserRequest $request): JsonResponse
    {
        try {
            $userIds = $request->input('user_id', []);

            $auth = auth('api')->user();
            if (in_array($auth->id, $userIds)) {
                return $this->responseError('Lỗi không thể xoá người dùng đang sử dụng!');
            }

            $condition[] = ['id', 'in', $userIds];
            $this->userRepository->deleteBy($condition);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete select user', [
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

            $this->userRepository->updateById($id, [
                'password' => $password,
                'updated_by' => auth()->id()
            ]);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error update user', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function resetMyPassword(ResetMyPasswordRequest $request): JsonResponse
    {
        try {
            $user = auth('api')->user();

            if (!Hash::check($request->input('password_old', ''), $user->password)) {
                return $this->responseError('', [
                    'password_old' => ['Mật khẩu cũ không chính xác!']
                ], '400');
            }

            $password = $request->input('password', '');

            $this->userRepository->updateById($user->id, [
                'password' => $password,
                'updated_by' => auth()->id()
            ]);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error reset my password user', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function updateProfile(UpdateUserRequest $request): JsonResponse
    {
        try {
            $auth = auth('api')->user();

            $data = $request->all();
            if ($request->hasFile('image')) {
                $path = Storage::disk('public')->putFile('images/user/thumbnail', $request->file('image'));
                $data['thumbnail'] = $path;
            }

            $this->userRepository->updateById($auth->id, array_merge($data, [
                'updated_by' => auth()->id(),
            ]));

            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error update profile', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function getTemplateImportFile()
    {
        $file = public_path() . '/template/student_temple.xlsx';
        return response()->download($file, 'student_temple.xlsx');
    }
}
