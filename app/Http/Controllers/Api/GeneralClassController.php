<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralClass\StoreGeneralClassRequest;
use App\Http\Requests\GeneralClass\UpdateGeneralClassRequest;
use App\Repositories\GeneralClass\GeneralClassRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GeneralClassController extends Controller
{
    use ResponseTrait;

    public function __construct(private GeneralClassRepositoryInterface $generalClassRepository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        $relationships = ['department', 'teacher', 'students'];
        $columns = ['*'];
        $paginate = $data['limit'] ?? config('constants.limit_of_paginate', 10);
        $condition = [];

        if (isset($data['q'])) {
            $condition[] = ['name', 'like', '%' . $data['q'] . '%'];
            $orCondition = [
                ['class_code', 'like', '%' . $data['q'] . '%'],
            ];
            $condition[] = ['name', 'or', $orCondition];
        }

        if (isset($data['class_code'])) {
            $condition[] = ['class_code' => $data['class_code']];
        }
        $user = auth()->user();

        if (@$user->teacher_id) {
            $condition[] = ['teacher_id' => $user->teacher_id];
        }

        $class = $this->generalClassRepository->getListPaginateBy($condition, $relationships, $columns, $paginate);

        return $this->responseSuccess(['class' => $class]);
    }

    public function show($id): JsonResponse
    {
        $relationships = ['students', 'department'];
        $columns = ['*'];

        return $this->responseSuccess([
            'class' => $this->generalClassRepository->findById($id, $columns, $relationships)
        ]);
    }

    public function store(StoreGeneralClassRequest $request): JsonResponse
    {
        try {
            $data = $request->all();
            $class = $this->generalClassRepository->create(array_merge($data, [
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]));
            return $this->responseSuccess(['class' => $class]);

        } catch (\Exception $exception) {
            Log::error('Error store class', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function update(UpdateGeneralClassRequest $request, $id): JsonResponse
    {
        try {
            $data = $request->all();
            $class = $this->generalClassRepository->findById($id);
            $class?->fill(array_merge($data, [
                'updated_by' => auth()->id(),
            ]));
            $this->generalClassRepository->createOrUpdate($class);

            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error update class', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->generalClassRepository->deleteById($id);

            return $this->responseSuccess();
        } catch (\Exception $exception) {
            Log::error('Error delete class', [
                'method' => __METHOD__,
                'message' => $exception->getMessage()
            ]);
            return $this->responseError();
        }
    }
}
