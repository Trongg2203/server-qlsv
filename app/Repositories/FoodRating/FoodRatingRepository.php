<?php

namespace App\Repositories\FoodRating;

use App\Models\FoodRatingModel;
use App\Repositories\BaseRepository;

class FoodRatingRepository extends BaseRepository implements IFoodRatingRepository
{
    protected $model;

    public function __construct(FoodRatingModel $model)
    {
        $this->model = $model;
    }

    public function getByFood(string $foodId): array
    {
        $data = $this->model
            ->with('user:id,name')
            ->where('food_id', $foodId)
            ->orderByDesc('updated_at')
            ->get();

        return ['total' => $data->count(), 'data' => $data];
    }

    public function getByUser(string $userId): array
    {
        $data = $this->model
            ->with('food:id,name,calories')
            ->where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->get();

        return ['total' => $data->count(), 'data' => $data];
    }

    /**
     * Trả về ma trận user × food rating — dùng cho Collaborative Filtering.
     * Format: [['user_id'=>..., 'food_id'=>..., 'rating'=>...], ...]
     */
    public function getRatingMatrix(): array
    {
        return $this->model
            ->select('user_id', 'food_id', 'rating')
            ->get()
            ->toArray();
    }

    /**
     * Tạo mới hoặc cập nhật rating (mỗi user chỉ rate 1 lần / 1 món).
     */
    public function upsert(array $data): object
    {
        // Check if rating exists
        $existing = $this->model
            ->where('user_id', $data['user_id'])
            ->where('food_id', $data['food_id'])
            ->first();

        if ($existing) {
            $existing->update([
                'rating'  => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]);
            return $existing;
        }

        return $this->model->create([
            'id'      => generateRandomString(),
            'user_id' => $data['user_id'],
            'food_id' => $data['food_id'],
            'rating'  => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
    }
}
