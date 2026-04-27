<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Food\StoreFoodCategoryRequest;
use App\Services\FoodCategoryService;
use Symfony\Component\HttpFoundation\Response;

class FoodCategoryController extends BaseApiController
{
    public function __construct(FoodCategoryService $service)
    {
        $this->_service = $service;
    }

    public function index()
    {
        $data = $this->_service->getAllOrdered();
        return $this->successResponse($data);
    }

    public function store(StoreFoodCategoryRequest $request)
    {
        $result = $this->_service->store($request->validated());
        if ($result) {
            return $this->successResponse($result, 'Tạo danh mục thành công.', Response::HTTP_CREATED);
        }
        return $this->errorResponse('Tạo danh mục thất bại.');
    }

    public function show(string $id)
    {
        $data = $this->_service->detail($id);
        return $this->successResponse($data);
    }

    public function update(StoreFoodCategoryRequest $request, string $id)
    {
        $data       = $request->validated();
        $data['id'] = $id;
        $result     = $this->_service->update($data);
        if ($result) {
            return $this->successResponse($result, 'Cập nhật danh mục thành công.');
        }
        return $this->errorResponse('Cập nhật thất bại.');
    }

    public function destroy(string $id)
    {
        return $this->delete($id);
    }
}
