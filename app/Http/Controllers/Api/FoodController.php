<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Food\StoreFoodRequest;
use App\Services\FoodService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FoodController extends BaseApiController
{
    public function __construct(FoodService $service)
    {
        $this->_service = $service;
    }

    /**
     * Danh sách tất cả món ăn — endpoint chính để Python lấy dữ liệu.
     * GET /api/foods?meal_type=1&category_id=xxx
     */
    public function index(Request $request)
    {
        $data = $this->_service->getForAi($request->only(['meal_type', 'category_id']));
        return $this->successResponse($data);
    }

    public function show(string $id)
    {
        $data = $this->_service->detail($id);
        return $this->successResponse($data);
    }

    public function byCategory(string $categoryId)
    {
        $data = $this->_service->getByCategory($categoryId);
        return $this->successResponse($data);
    }

    public function store(StoreFoodRequest $request)
    {
        $result = $this->_service->store($request->validated());
        if ($result) {
            return $this->successResponse($result, 'Thêm món ăn thành công.', Response::HTTP_CREATED);
        }
        return $this->errorResponse('Thêm món ăn thất bại.');
    }

    public function update(StoreFoodRequest $request, string $id)
    {
        $data       = $request->validated();
        $data['id'] = $id;
        $result     = $this->_service->update($data);
        if ($result) {
            return $this->successResponse($result, 'Cập nhật món ăn thành công.');
        }
        return $this->errorResponse('Cập nhật thất bại.');
    }

    public function destroy(string $id)
    {
        return $this->delete($id);
    }
}
