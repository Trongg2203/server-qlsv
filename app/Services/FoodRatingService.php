<?php

namespace App\Services;

use App\Repositories\FoodRating\IFoodRatingRepository;

class FoodRatingService extends BaseService
{
    public function __construct(IFoodRatingRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getByFood(string $foodId): array
    {
        return $this->repo->getByFood($foodId);
    }

    public function getMyRatings(): array
    {
        $userId = auth()->guard('api')->id();
        return $this->repo->getByUser($userId);
    }

    public function getRatingMatrix(): array
    {
        return $this->repo->getRatingMatrix();
    }

    /**
     * Tạo hoặc cập nhật rating (upsert).
     */
    public function rateFood(array $data): object
    {
        $data['user_id'] = auth()->guard('api')->id();
        return $this->repo->upsert($data);
    }
}
