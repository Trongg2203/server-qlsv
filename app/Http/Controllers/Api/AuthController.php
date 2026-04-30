<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\UserProfile\StoreUserProfileRequest;
use App\Http\Requests\UserProfile\UpdateUserProfileRequest;
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
        $data = $this->_service->getMyProfile();
        return $this->successResponse($data);
    }
    
    public function updateProfile(UpdateUserProfileRequest $request)
    {
        $result = $this->_service->updateMyProfile($request->validated());
        
        if ($result) {
            return $this->successResponse($result, 'Cập nhật hồ sơ thành công.');
        }
        
        return $this->errorResponse('Không tìm thấy hồ sơ người dùng.', Response::HTTP_NOT_FOUND);
    }

    public function createProfile(StoreUserProfileRequest $request)
    {
        $result = $this->_service->createMyProfile($request->validated());

        if ($result === 'exists') {
            return $this->errorResponse('Hồ sơ đã tồn tại.', Response::HTTP_CONFLICT);
        }

        if ($result) {
            return $this->successResponse($result, 'Tạo hồ sơ thành công.', Response::HTTP_CREATED);
        }

        return $this->errorResponse('Không tìm thấy người dùng.', Response::HTTP_NOT_FOUND);
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
