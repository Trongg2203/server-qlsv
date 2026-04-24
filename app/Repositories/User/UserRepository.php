<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\IBaseRepository;
use Illuminate\Support\Facades\Hash;

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

    public function findByEmailAndPassword($email, $password)
    {
        $user = $this->model->where('email', $email)->first();

        if ($user && $user->password === md5($password)) {
            return $user;
        }

        return null;
    }

    public function get()
    {
        $data = $this->model->where('id', '!=', SUPER_ADMIN_ID)->get();

        return $data;
    }


    public function changePassword($data)
    {
        $user = $this->findByEmail($data['email']);

        if (!$user) {
            return null;
        }


        // cập nhật password mới
        $user->password = md5($data['new_password']);
        $user->save();

        return $user;
    }
}
