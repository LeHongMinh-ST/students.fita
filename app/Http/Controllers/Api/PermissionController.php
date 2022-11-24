<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\GroupPermission\GroupPermissionRepositoryInterface;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private PermissionRepositoryInterface $permissionRepository,
        private GroupPermissionRepositoryInterface $groupPermissionRepository
    )
    {
    }

    public function index(): JsonResponse
    {
        return $this->responseSuccess(['permissions' => $this->permissionRepository->all()]);
    }

    public function getGroupPermission(): JsonResponse
    {
        return $this->responseSuccess(['groupPermission' => $this->groupPermissionRepository->all(['*'], ['permissions'])]);
    }
}
