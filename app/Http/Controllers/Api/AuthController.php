<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseApiController
{
    private $_userService;

    public function __construct(UserService $userService)
    {
        $this->_service = $userService;
        // $this->accessPermissionService = $accessPermissionService;
    }



    public function login(LoginRequest $loginRequest)
    {
        $data = $loginRequest->all();

        $user = $this->_service->login($data['email'], $data['password']);

        if ($user) {
            $token = auth()->guard('api')->login($user);


            if (!$token) {
                return $this->errorResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
            }

            $result = [
                'access_token' => $token,
                'expires_in' => JWTAuth::factory()->getTTL() * 6000,
                'isLoggedIn' => true,
                'user_type' => $user->type,
                'is_admin' => $user->type !== UserType::USER ? true : false
            ];

            return $this->successResponse($result, __('auth.login-success'));
        }
        return $this->errorResponse(__('auth.login-fail'), Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        auth()->guard('api')->logout();
        return $this->successResponse(null, __('auth.logout-success'));
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

    public function register(Request $request)
    {
        $data = $request->all();

        $user = $this->_service->findByEmail($data['email']);

        //  email đã tồn tại
        if ($user) {
            return $this->errorResponse('Email đã tồn tại', 400);
        }

        // tạo user mới
        $newUser = $this->_service->createUser($data);

        return $this->successResponse($newUser, 'Đăng ký thành công');
    }
}
