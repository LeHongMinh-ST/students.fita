<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ResponseTrait;

    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->responseSuccess(['users' => $this->userRepository->getListPaginateBy($request->all())]);
    }

    public function show($id): JsonResponse
    {
        return $this->responseSuccess(['user' => $this->userRepository->findById($id)]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            return $this->responseSuccess(['user' => $this->userRepository->create($request->all())]);
        } catch (\Exception $exception) {
            Log::error('Error store user', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {
            $this->userRepository->updateById($id, $request->all());
            return $this->responseSuccess();
        } catch (\Exception $exception) {
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

    public function resetPassword(ResetPasswordRequest $request, $id): JsonResponse
    {
        try {
            $password = $request->input('password', '');

            $this->userRepository->updateById($id, ['password' => Hash::make($password)]);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error update user', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }
}
