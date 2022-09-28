<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private RoleRepositoryInterface $roleRepository
    )
    {
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $relationships = ['permissions'];
        $columns = ['*'];
        $paginate = $data['limit'] ?? config('constants.limit_of_paginate', 10);
        $condition = [];

        if (isset($data['q'])) {
            $condition[] = ['name', 'like', '%' . $data['q'] . '%'];
        }

        $roles = $this->roleRepository->getListPaginateBy($condition, $relationships, $columns, $paginate);

        return $this->responseSuccess([
            'roles' => $roles
        ]);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $authId = auth()->id();
            $role = $this->roleRepository->create(array_merge($data, [
                'created_by' => $authId,
                'updated_by' => $authId
            ]));
            $role?->permissions()->attach($data['permission_ids'] ?? []);
            DB::commit();
            return $this->responseSuccess(['role' => $role]);

        } catch (\Exception $exception) {
            DB::rollBack();

            Log::error('Error store role', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateRoleRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $role = $this->roleRepository->findById($id);
            $role->fill(array_merge($data, [
                'updated_by' => auth()->id()
            ]));

            $role = $this->roleRepository->createOrUpdate($role);

            $role->permissions()->sync($data['permission_ids'] ?? []);
            DB::commit();
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error update role', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }


    public function destroy($id): JsonResponse
    {
        try {
            $this->roleRepository->deleteById($id);
            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete role', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }
}
