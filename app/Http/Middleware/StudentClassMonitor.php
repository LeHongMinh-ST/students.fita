<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use App\Enums\Student\StudentRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudentClassMonitor
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (auth('students')->check()) {
            $auth = auth('students')->user();
            if ($auth->role == StudentRole::ClassMonitor) {
                return $next($request);
            }
        }

        return $this->responseError(
            'Bạn không có quyền truy cập vào chức năng này!',
            [],
            Response::HTTP_FORBIDDEN,
            403
        );
    }
}
