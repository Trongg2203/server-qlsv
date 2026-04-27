<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Enums\UserType;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseApiController
{
    public function __construct(UserService $userService)
    {
        $this->_service = $userService;
    }

    public function login(LoginRequest $loginRequest)
    {
        $data = $loginRequest->all();

        $user = $this->_service->login($data['email'], $data['password']);

        if ($user) {
            if ($user->account_status !== 1) {
                return $this->errorResponse(
                    'Tài khoản chưa được kích hoạt hoặc đã bị khoá.',
                    Response::HTTP_FORBIDDEN
                );
            }

            $token = auth()->guard('api')->login($user);

            if (!$token) {
                return $this->errorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
            }

            $result = [
                'access_token' => $token,
                'expires_in'   => JWTAuth::factory()->getTTL() * 60,
                'isLoggedIn'   => true,
                'user_type'    => $user->role,
                'is_admin'     => $user->role === UserType::ADMIN,
            ];

            return $this->successResponse($result, __('auth.login-success'));
        }

        return $this->errorResponse(__('auth.login-fail'), Response::HTTP_UNAUTHORIZED);
    }

    public function me()
    {
        return $this->successResponse(auth('api')->user());
    }

    public function logout()
    {
        try {
            $token = JWTAuth::parseToken();
            JWTAuth::invalidate($token);
            auth()->guard('api')->logout();
        } catch (JWTException $e) {
            // token đã hết hạn hoặc không hợp lệ — vẫn coi là logout thành công
        }

        return $this->successResponse(null, __('auth.logout-success'));
    }

    public function refresh(Request $request)
    {
        try {
            $token = $request->bearerToken();
            JWTAuth::setToken($token);
            $user = JWTAuth::authenticate();

            if (!$user) {
                return $this->errorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
            }

            JWTAuth::factory()->setTTL((int) env('JWT_TTL', 60));
            $newAccessToken = JWTAuth::fromUser($user);

            return $this->successResponse([
                'access_token' => $newAccessToken,
                'expires_in'   => (int) env('JWT_TTL', 60) * 60,
            ]);
        } catch (JWTException $e) {
            return $this->errorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }
    }

    public function getDetail()
    {
        $data = $this->_service->getDetail();
        return $this->successResponse($data);
    }

    public function getProfile()
    {
        $data = $this->_service->getProfile();
        return $this->successResponse($data);
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->_service->register($request->validated());
            return $this->successResponse(['id' => $user->id, 'email' => $user->email], 'Đăng ký thành công.', Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return $this->errorResponse('Đăng ký thất bại: ' . $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $profile = $this->_service->updateProfile($request->validated());
        if (!$profile) {
            return $this->errorResponse('Không tìm thấy hồ sơ.', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($profile, 'Cập nhật hồ sơ thành công.');
    }
}

