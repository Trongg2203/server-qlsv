<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserGoal\AddUserGoalRequest;
use App\Services\UserGoalService;
use Illuminate\Container\Attributes\Auth;

class UserGoalController extends BaseApiController
{
    public function __construct(UserGoalService $userGoalService)
    {
        $this->_service = $userGoalService;
    }

    function createUserGoal(AddUserGoalRequest $request)
    {
        $data = $request->all();
        $result  =  $this->_service->createUserGoal($data);
        if ($result)
            return $this->successResponse(__('common.add-success'));
        return $this->errorResponse(__('common.add-fail'));
    }

    function getBySelf()
    {
        $data = $this->_service->getBySelf();
        return $this->successResponse($data);
    }
}
