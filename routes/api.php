<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthStudentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\GeneralClassController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('social/{provider}', [AuthController::class, 'redirectToProvider']);
    Route::post('social/{provider}/callback', [AuthController::class, 'callbackProvider']);

    Route::group(['middleware' => ['jwt.auth', 'auth.admin']], function () {
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::group(['middleware' => ['jwt.auth', 'auth.admin']], function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->middleware('permission:dashboard-index');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::delete('/delete-selected', [UserController::class, 'deleteSelected'])->middleware('permission:user-delete');
        Route::post('/', [UserController::class, 'store'])->middleware('permission:user-create');
        Route::get('/{id}', [UserController::class, 'show'])->middleware('permission:user-index');
        Route::put('/{id}', [UserController::class, 'update'])->middleware('permission:user-update');
        Route::put('/{id}/reset-password', [UserController::class, 'resetPassword'])->middleware('permission:user-update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('permission:user-delete');
    });

    Route::prefix('profile')->group(function () {
        Route::put('/reset-password', [UserController::class, 'resetMyPassword']);
        Route::post('', [UserController::class, 'updateProfile']);

    });

    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->middleware('permission:student-index');
        Route::get('/request/count', [StudentController::class, 'getCountRequest'])->middleware('permission:student-update');
        Route::get('/request', [StudentController::class, 'getRequestUpdateStudent'])->middleware('permission:student-update');
        Route::put('/request/{id}', [StudentController::class, 'updateStudentByStudentTemp'])->middleware('permission:student-update');
        Route::put('/request/selected', [StudentController::class, 'updateStudentByStudentTempMultiple'])->middleware('permission:student-update');
        Route::post('/', [StudentController::class, 'store'])->middleware('permission:student-create');
        Route::put('/update-learning-outcome/{id}', [StudentController::class, 'updateDataLearningOutcome'])->middleware('permission:student-update');
        Route::get('/{id}', [StudentController::class, 'show'])->middleware('permission:student-index');
        Route::post('/{id}', [StudentController::class, 'update'])->middleware('permission:student-update');
        Route::put('/{id}/reset-password', [StudentController::class, 'resetPassword'])->middleware('permission:student-update');
        Route::delete('/{id}', [StudentController::class, 'destroy'])->middleware('permission:student-delete');
    });

    Route::prefix('classes')->group(function () {
        Route::get('/', [GeneralClassController::class, 'index'])->middleware('permission:class-index');
        Route::get('/all', [GeneralClassController::class, 'getALl'])->middleware('permission:class-index');
        Route::post('/', [GeneralClassController::class, 'store'])->middleware('permission:class-create');
        Route::get('/download/get-template-import-file', [StudentController::class, 'getTemplateImportFile']);
        Route::get('/{id}', [GeneralClassController::class, 'show'])->middleware('permission:class-index');
        Route::put('/{id}', [GeneralClassController::class, 'update'])->middleware('permission:class-update');
        Route::put('/{id}/add-student', [GeneralClassController::class, 'addStudentToClass'])->middleware('permission:class-update');
        Route::post('/{id}/import-student', [StudentController::class, 'importStudentToClass'])->middleware(['permission:class-update', 'permission:student-create']);
        Route::delete('/{id}', [GeneralClassController::class, 'destroy'])->middleware('permission:class-delete');
    });

    Route::prefix('departments')->group(function () {
        Route::get('/', [DepartmentController::class, 'index']);
        Route::get('/all', [DepartmentController::class, 'getAll'])->middleware('permission:department-index');
        Route::post('/', [DepartmentController::class, 'store'])->middleware('permission:department-create');
        Route::put('/{id}', [DepartmentController::class, 'update'])->middleware('permission:department-update');
        Route::delete('/delete-selected', [DepartmentController::class, 'deleteSelected'])->middleware('permission:department-delete');
        Route::delete('/{id}', [DepartmentController::class, 'destroy'])->middleware('permission:department-delete');
        Route::get('/get-all-id', [DepartmentController::class, 'getAllId']);
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->middleware('permission:role-index');
        Route::get('/get-all-role-id', [RoleController::class, 'getAllRoleId'])->middleware('permission:role-index');
        Route::delete('/delete-selected', [RoleController::class, 'deleteSelected'])->middleware('permission:role-delete');
        Route::post('/', [RoleController::class, 'store'])->middleware('permission:role-create');
        Route::get('/{id}', [RoleController::class, 'show'])->middleware('permission:role-index');
        Route::put('/{id}', [RoleController::class, 'update'])->middleware('permission:role-update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->middleware('permission:role-delete');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->middleware('permission:report-index');
        Route::get('/count-pending', [ReportController::class, 'getCountReportPending'])->middleware('permission:report-index');
        Route::post('/', [ReportController::class, 'store'])->middleware('permission:report-create');
        Route::get('/{id}', [ReportController::class, 'show'])->middleware('permission:report-index');
        Route::put('/{id}', [ReportController::class, 'update'])->middleware('permission:report-update');
        Route::delete('/{id}', [ReportController::class, 'destroy'])->middleware('permission:report-delete');
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->middleware('permission:role-index');
        Route::get('/group', [PermissionController::class, 'getGroupPermission'])
            ->middleware('permission:role-index');
    });

});

Route::group(['prefix' => 'student'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthStudentController::class, 'login']);
        Route::post('logout', [AuthStudentController::class, 'logout']);
        Route::get('social/{provider}', [AuthStudentController::class, 'redirectToProvider']);
        Route::post('social/{provider}/callback', [AuthStudentController::class, 'callbackProvider']);

        Route::group(['middleware' => ['auth.student']], function () {
            Route::get('me', [AuthStudentController::class, 'me']);
        });
    });


    Route::group(['middleware' => ['auth.student']], function () {
        Route::prefix('profile')->group(function () {
            Route::get('/', [StudentController::class, 'getProfileStudent']);
            Route::put('/update-learning-outcome/{id}', [StudentController::class, 'updateDataLearningOutcome']);
            Route::put('/reset-password', [StudentController::class, 'resetMyPassword']);
            Route::put('/{id}', [StudentController::class, 'updateProfile']);
        });

        Route::prefix('requests')->group(function () {
            Route::get('/', [StudentController::class, 'getRequestUpdateStudent']);
            Route::post('/', [StudentController::class, 'createStudentTemp']);
            Route::put('/{id}', [StudentController::class, 'updateStudentByStudentTemp']);
        });

        Route::get('/class', [StudentController::class, 'getClass']);

        Route::prefix('/requests')->group(function () {
            Route::get('/', [StudentController::class, 'getRequestUpdateStudent']);
            Route::get('/my-request', [StudentController::class, 'getMyRequestUpdateStudent']);
            Route::post('/', [StudentController::class, 'createStudentTemp']);
            Route::put('/selected', [StudentController::class, 'updateStudentByStudentTempMultiple']);
            Route::put('/{id}', [StudentController::class, 'updateStudentByStudentTemp']);
        });

        Route::group(['middleware' => ['student.class-monitor']], function () {
            Route::prefix('report')->group(function () {
                Route::get('/', [ReportController::class, 'index']);
                Route::post('/', [ReportController::class, 'store']);
                Route::get('/{id}', [ReportController::class, 'show']);
                Route::put('/{id}', [ReportController::class, 'update']);
                Route::delete('/{id}', [ReportController::class, 'destroy']);
            });
            Route::get('/get-student-class-monitor', [StudentController::class, 'getStudentClassMonitor']);
        });

    });
});
