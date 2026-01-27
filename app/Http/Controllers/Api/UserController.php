<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseApiController
{
    private $_userService;

    public function __construct(UserService $userService)
    {
        $this->_service = $userService;
        // $this->accessPermissionService = $accessPermissionService;
    }


    public function get()
    {
        $data = $this->_service->get();
        return $this->successResponse($data);
    }
}
