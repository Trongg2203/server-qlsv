<?php

namespace App\Repositories\ProductImage;

use App\Models\ProductImageModel;
use App\Repositories\BaseRepository;

class ProductImageRepository extends BaseRepository implements IProductImageRepository
{
    protected $model;

    public function __construct(ProductImageModel $model)
    {
        $this->model = $model;
    }

    public function getByFood(string $foodId): array
    {
        $data = $this->model
            ->where('food_id', $foodId)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();

        return ['total' => $data->count(), 'data' => $data];
    }

    public function deleteById(string $id): bool
    {
        return (bool) $this->model->where('id', $id)->delete();
    }
}
