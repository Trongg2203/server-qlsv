<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
use App\Http\Requests\ModuleAction\AddModuleActionRequest;
use App\Services\ModuleActionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuleActionController extends BaseApiController
{

    protected $_service;
    public function __construct(ModuleActionService $moduleActionService)
    {
        $this->_service = $moduleActionService;
    }

    public function createModuleAction(AddModuleActionRequest $request): JsonResponse
    {
        $user = auth()->guard('api')->user();

        if ($user->type !== UserType::SUPER_ADMIN && $user->type !== UserType::ADMIN) {
            return $this->errorResponse(__('common.permission-denied'), 403);
        }

        $data = $request->all();

        try {
            $moduleAction = $this->_service->createModuleAction($data);
            return $this->successResponse($moduleAction);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
