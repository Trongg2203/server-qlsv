<?php

namespace App\Repositories\Food;

use App\Models\FoodModel;
use App\Repositories\BaseRepository;

class FoodRepository extends BaseRepository implements IFoodRepository
{
    protected $model;

    public function __construct(FoodModel $model)
    {
        $this->model = $model;
    }

    /**
     * Lấy danh sách món ăn cho AI (đầy đủ thông tin dinh dưỡng).
     * Python sẽ gọi endpoint này để lấy dữ liệu để generate meal plan.
     */
    public function getForAi(array $filters = []): array
    {
        $query = $this->model->with('category:id,name')
            ->select([
                'id', 'category_id', 'name', 'serving_size', 'serving_unit',
                'calories', 'protein', 'carbs', 'fat', 'meal_type', 'popularity_score',
            ]);

        if (!empty($filters['meal_type'])) {
            $query->whereIn('meal_type', [0, (int) $filters['meal_type']]);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        $data = $query->orderByDesc('popularity_score')->get();

        return ['total' => $data->count(), 'data' => $data];
    }

    public function getByCategory(string $categoryId): array
    {
        $data = $this->model
            ->where('category_id', $categoryId)
            ->get();

        return ['total' => $data->count(), 'data' => $data];
    }

    public function incrementPopularity(array $foodIds): void
    {
        $this->model->whereIn('id', $foodIds)->increment('popularity_score');
    }
}
