<?php

namespace App\Services;

use App\Repositories\FoodCategory\IFoodCategoryRepository;

class FoodCategoryService extends BaseService
{
    public function __construct(IFoodCategoryRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAllOrdered(): array
    {
        return $this->repo->getAllOrdered();
    }

    public function store(array $data): object
    {
        $data['id'] = generateRandomString();
        return $this->repo->create($data);
    }

    public function update($data): object
    {
        return $this->repo->update($data['id'], $data);
    }
}
