<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Social\SocialRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private UserRepositoryInterface   $userRepository,
        private SocialRepositoryInterface $socialRepository
    )
    {
    }

    public function login(): JsonResponse
    {
        request()->merge([$this->username() => request()->input('user_name')]);
        $credentials = request([$this->username(), 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return $this->responseError('Unauthorized', [], 401, 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Đăng xuất thành công']);
    }

    public function me(): JsonResponse
    {
        $user = auth('api')->user();

        if (empty($user)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        };

        return response()->json($user);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    private function username(): string
    {
        return filter_var(request()->input('user_name'), FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';
    }

    public function redirectToProvider($provider): JsonResponse
    {
        $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
        return $this->responseSuccess([
            'url' => $url
        ]);
    }

    public function callbackProvider($provider): JsonResponse
    {
        $user = null;

        try {
            $userProvider = Socialite::driver($provider)->stateless()->user();

        } catch (\Exception $e) {
            Log::error('Error handle login social', [
                'method' => __METHOD__,
                'message' => $e->getMessage()
            ]);

            return $this->responseError("Đã xảy ra lỗi khi cố gắng đăng nhập bằng {$provider}", [], 422);
        }


        if (empty($userProvider)) {
            return $this->responseError('Invalid user provider', [], 422);
        }

        if (empty($userProvider->id)) {
            return $this->responseError('Invalid provider id', [], 422);
        }

        if (empty($userProvider->name)) {
            return $this->responseError('Invalid provider name', [], 422);
        }

        if (empty($userProvider->email)) {
            return $this->responseError('Invalid provider email', [], 422);
        }

        $social = $this->socialRepository->getFirstBy([
            'social_id' => $userProvider->id,
            'social_provider' => $provider,
            'socialable_type' => User::class
        ]);


        if (!$social) {
            return $this->responseError('Bạn chưa liên kết với tài khoản nào !');
        }

        $user = $this->userRepository->findById($social->socialable_id);

        if (!$user) {
            return $this->responseError('Không tìm thấy tài khoản!');
        }

        if (!$token = auth()->attempt(['email' => $userProvider->email])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
}
