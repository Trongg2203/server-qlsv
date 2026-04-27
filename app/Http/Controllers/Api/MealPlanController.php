<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\MealPlan\GenerateMealPlanRequest;
use App\Services\MealPlanService;
use Symfony\Component\HttpFoundation\Response;

class MealPlanController extends BaseApiController
{
    public function __construct(MealPlanService $service)
    {
        $this->_service = $service;
    }

    /**
     * Lấy tất cả meal plans của user.
     */
    public function index()
    {
        $data = $this->_service->getMyPlans();
        return $this->successResponse($data);
    }

    /**
     * Lấy meal plan đang active (kèm đầy đủ details).
     */
    public function active()
    {
        $data = $this->_service->getActivePlan();
        return $this->successResponse($data);
    }

    /**
     * Tạo meal plan 7 ngày mới bằng AI.
     * POST /api/meal-plans/generate
     */
    public function generate(GenerateMealPlanRequest $request)
    {
        $result = $this->_service->generate($request->validated());
        if ($result) {
            return $this->successResponse($result, 'Tạo thực đơn 7 ngày thành công.', Response::HTTP_CREATED);
        }
        return $this->errorResponse('Tạo thực đơn thất bại.');
    }
}
