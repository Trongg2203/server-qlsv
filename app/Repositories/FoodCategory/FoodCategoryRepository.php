<?php

namespace App\Repositories\FoodCategory;

use App\Models\FoodCategoryModel;
use App\Repositories\BaseRepository;

class FoodCategoryRepository extends BaseRepository implements IFoodCategoryRepository
{
    protected $model;

    public function __construct(FoodCategoryModel $model)
    {
        $this->model = $model;
    }

    public function getAllOrdered(): array
    {
        $data = $this->model->orderBy('sort_order')->get();
        return ['total' => $data->count(), 'data' => $data];
    }
}
