<?php

namespace App\Repositories\FoodRating;

use App\Repositories\IBaseRepository;

interface IFoodRatingRepository extends IBaseRepository
{
    public function getByFood(string $foodId): array;
    public function getByUser(string $userId): array;
    public function getRatingMatrix(): array;
    public function upsert(array $data): object;
}
