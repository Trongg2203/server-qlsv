<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Goal\StoreUserGoalRequest;
use App\Http\Requests\Goal\UpdateUserGoalRequest;
use App\Services\UserGoalService;
use Symfony\Component\HttpFoundation\Response;

class UserGoalController extends BaseApiController
{
    public function __construct(UserGoalService $service)
    {
        $this->_service = $service;
    }

    public function index()
    {
        $data = $this->_service->getMyGoals();
        return $this->successResponse($data);
    }

    public function active()
    {
        $data = $this->_service->getActiveGoal();
        return $this->successResponse($data);
    }

    public function store(StoreUserGoalRequest $request)
    {
        $result = $this->_service->createGoal($request->validated());
        if ($result) {
            return $this->successResponse($result, 'Mục tiêu đã được tạo thành công.', Response::HTTP_CREATED);
        }
        return $this->errorResponse('Tạo mục tiêu thất bại.');
    }

    public function update(UpdateUserGoalRequest $request, string $id)
    {
        $data       = $request->validated();
        $data['id'] = $id;
        $result     = $this->_service->updateGoal($data);
        if ($result) {
            return $this->successResponse($result, 'Mục tiêu đã được cập nhật.');
        }
        return $this->errorResponse('Cập nhật thất bại.');
    }

    public function destroy(string $id)
    {
        return $this->delete($id);
    }
}
