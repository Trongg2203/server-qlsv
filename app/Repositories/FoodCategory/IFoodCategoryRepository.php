<?php

namespace App\Repositories\FoodCategory;

use App\Repositories\IBaseRepository;

interface IFoodCategoryRepository extends IBaseRepository
{
    public function getAllOrdered(): array;
}
