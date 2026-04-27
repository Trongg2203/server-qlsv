<?php

namespace App\Services;

use App\Repositories\Food\IFoodRepository;

class FoodService extends BaseService
{
    public function __construct(IFoodRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Danh sách tất cả món ăn — dùng cho Python AI.
     * Trả về đầy đủ thông tin dinh dưỡng.
     */
    public function getForAi(array $filters = []): array
    {
        return $this->repo->getForAi($filters);
    }

    public function getByCategory(string $categoryId): array
    {
        return $this->repo->getByCategory($categoryId);
    }

    public function store(array $data): object
    {
        $data['id']         = generateRandomString();
        $data['created_at'] = now();
        return $this->repo->create($data);
    }

    public function update($data): object
    {
        return $this->repo->update($data['id'], $data);
    }

    public function incrementPopularity(array $foodIds): void
    {
        $this->repo->incrementPopularity($foodIds);
    }
}
