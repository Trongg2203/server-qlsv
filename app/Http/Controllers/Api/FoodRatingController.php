<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Food\StoreFoodRatingRequest;
use App\Services\FoodRatingService;
use Symfony\Component\HttpFoundation\Response;

class FoodRatingController extends BaseApiController
{
    public function __construct(FoodRatingService $service)
    {
        $this->_service = $service;
    }

    /**
     * Ratings của một món ăn cụ thể.
     */
    public function byFood(string $foodId)
    {
        $data = $this->_service->getByFood($foodId);
        return $this->successResponse($data);
    }

    /**
     * Tất cả ratings của user đang đăng nhập.
     */
    public function myRatings()
    {
        $data = $this->_service->getMyRatings();
        return $this->successResponse($data);
    }

    /**
     * Ma trận user × food — dùng cho Python Collaborative Filtering.
     * GET /api/food-ratings/matrix (nội bộ, chỉ Python gọi)
     */
    public function ratingMatrix()
    {
        $data = $this->_service->getRatingMatrix();
        return $this->successResponse($data);
    }

    /**
     * Tạo hoặc cập nhật rating (upsert).
     */
    public function rate(StoreFoodRatingRequest $request)
    {
        $result = $this->_service->rateFood($request->validated());
        if ($result) {
            return $this->successResponse($result, 'Đánh giá đã được lưu.', Response::HTTP_CREATED);
        }
        return $this->errorResponse('Lưu đánh giá thất bại.');
    }
}
