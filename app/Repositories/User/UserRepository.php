<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\IBaseRepository;

class UserRepository extends BaseRepository implements IUserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findByUserName($name)
    {
        $data = $this->model
            ->where('name', $name)
            ->first();

        return $data;
    }

    public function findByEmail($email)
    {
        $data = $this->model
            ->where('email', $email)
            ->first();

        return $data;
    }

    public function get(){
        $data = $this->model->where('id','!=',SUPER_ADMIN_ID)->get();

        return $data;
    }
}
