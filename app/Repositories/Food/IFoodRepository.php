<?php

namespace App\Repositories\Food;

use App\Repositories\IBaseRepository;

interface IFoodRepository extends IBaseRepository
{
    public function getForAi(array $filters = []): array;
    public function getByCategory(string $categoryId): array;
    public function incrementPopularity(array $foodIds): void;
}
