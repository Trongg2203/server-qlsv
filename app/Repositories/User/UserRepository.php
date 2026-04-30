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

        if (!$user) {
            return null;
        }

        if ($this->isBcryptHash($user->password) && Hash::check($password, $user->password)) {
            return $user;
        }

        // Support legacy md5 passwords, then re-hash to bcrypt.
        if ($user->password === md5($password)) {
            $user->password = Hash::make($password);
            $user->save();
            return $user;
        }

        return null;
    }

    private function isBcryptHash($hash): bool
    {
        return is_string($hash) && preg_match('/^\$2[aby]\$/', $hash) === 1;
    }

    public function getAllActive()
    {
        $data = $this->model->where('id', '!=', SUPER_ADMIN_ID)->get();

        return $data;
    }

    public function get()
    {
        $this->model = $this->model->where('id', '!=', SUPER_ADMIN_ID);

        return parent::get($this->model);
    }


    public function changePassword($data)
    {
        $user = $this->findByEmail($data['email']);

        if (!$user) {
            return null;
        }


        // cập nhật password mới
        $user->password = Hash::make($data['new_password']);
        $user->save();

        return $user;
    }
}
