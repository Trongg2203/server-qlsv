<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class UserController extends BaseApiController
{
    private $_userService;

    public function __construct(UserService $userService)
    {
        $this->_service = $userService;
        // $this->accessPermissionService = $accessPermissionService;
    }


    public function getAllActive()
    {
        $data = $this->_service->getAllActive();
        return $this->successResponse($data);
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'new_password' => 'required|min:6'
        ]);

        $result = $this->_service->changePassword($data);

        if (!$result) {
            return response()->json([
                'code' => 400,
                'message' => 'Email hoặc password không đúng',
                'data' => null
            ], 400);
        }
        return $this->successResponse($result);
        // return response()->json([
        //     'code' => 200,
        //     'message' => 'Đổi mật khẩu thành công',
        //     'data' => $result
        // ]);
    }

    public function get()
    {
        $data = $this->_service->get();
        return $this->successResponse($data);
    }
    public function delete($id)
    {
        $result = $this->_service->delete($id);
        if ($result) {
            return $this->successResponse(null, 'Xóa thành công');
        }
        return $this->errorResponse('User not found', 404);
    }
}
