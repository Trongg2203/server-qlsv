<?php

namespace App\Repositories\ProductImage;

use App\Repositories\IBaseRepository;

interface IProductImageRepository extends IBaseRepository
{
    public function getByFood(string $foodId): array;
    public function deleteById(string $id): bool;
}
