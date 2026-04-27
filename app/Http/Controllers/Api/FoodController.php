<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Food\StoreFoodRequest;
use App\Services\FoodService;
use App\Services\ProductImageService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FoodController extends BaseApiController
{
    private ProductImageService $_imageService;

    public function __construct(FoodService $service, ProductImageService $imageService)
    {
        $this->_service      = $service;
        $this->_imageService = $imageService;
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

    /**
     * POST /api/foods/{id}/images
     * Body: multipart/form-data, field "images[]" (1 hoặc nhiều file)
     */
    public function uploadImages(Request $request, string $id)
    {
        $request->validate([
            'images'   => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $food = $this->_service->detail($id);
        if (!$food) {
            return $this->errorResponse('Không tìm thấy món ăn.', Response::HTTP_NOT_FOUND);
        }

        $uploaded = [];
        foreach ($request->file('images') as $index => $file) {
            $uploaded[] = $this->_imageService->upload($file, $id, $index);
        }

        return $this->successResponse(
            $uploaded,
            'Tải ảnh thành công.',
            Response::HTTP_CREATED
        );
    }

    /**
     * DELETE /api/foods/{id}/images/{imageId}
     */
    public function destroyImage(string $id, string $imageId)
    {
        $result = $this->_imageService->delete($imageId);
        if (!$result) {
            return $this->errorResponse('Không tìm thấy ảnh.', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse(null, 'Xoá ảnh thành công.');
    }
}
