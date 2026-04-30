<?php


namespace App\Repositories\UserProfile;

use App\Models\UserProfileModel;
use App\Repositories\BaseRepository;
use App\Repositories\UserProfile\IUserProfileRepository;

class UserProfileRepository extends BaseRepository implements IUserProfileRepository
{

    protected $model;

    public function __construct(UserProfileModel $model)
    {
        $this->model = $model;
    }

    public function getMyProfile()
    {
        $userId = auth()->guard('api')->id();
        if (!$userId) return;

        return $this->model
            ->with('user')
            ->where('user_id', $userId)
            ->first();
    }

    public function updateMyProfile(array $attributes)
    {
        $userId = auth()->guard('api')->id();
        if (!$userId) return false;

        $profile = $this->model->where('user_id', $userId)->first();
        if (!$profile) {
            return false;
        }

        $profile->update($attributes);

        return $this->getMyProfile();
    }

    public function createMyProfile(array $attributes)
    {
        $userId = auth()->guard('api')->id();
        if (!$userId) return false;

        $attributes['id'] = generateRandomString();
        $attributes['user_id'] = $userId;

        $created = $this->model->create($attributes);

        return $created ? $this->getMyProfile() : false;
    }
}
