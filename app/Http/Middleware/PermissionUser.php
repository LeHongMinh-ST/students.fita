<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\Role;
use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermissionUser
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $namePermission): mixed
    {
        try {
            $user = \auth('api')->user();
            $isSuperAdmin = $user->is_super_admin;
            if ($isSuperAdmin) {
                return $next($request);
            }

            $role = Role::find($user->role_id);
            if (!$role) {
                return $this->responseError(
                    'Bạn không có quyền truy cập vào chức năng này!',
                    [],
                    Response::HTTP_FORBIDDEN,
                    403
                );
            }
            if ($role->permissions) {
                $isPermission = $this->hasPermission($role, $namePermission);
                if ($isPermission) {
                    return $next($request);
                }
            }
            return $this->responseError(
                'Bạn không có quyền truy cập vào chức năng này!',
                [],
                Response::HTTP_FORBIDDEN,
                403
            );
        } catch (\Exception $e) {
            Log::error('Error middleware permission for user', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return $this->responseError();
        }

    }

    private function hasPermission($role, $codePermission)
    {
        $idPermission = Permission::where('code', $codePermission)->first();
        if ($idPermission)
            return $role->permissions->contains($idPermission->id);
        return false;
    }
}
