<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\UserService;
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
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ];

            return $this->successResponse($result, __('auth.login-success'));
        }
        return $this->errorResponse(__('auth.login-fail'), Response::HTTP_UNAUTHORIZED);
    }
}
