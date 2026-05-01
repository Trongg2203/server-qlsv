<?php

namespace App\Services;

use App\Repositories\User\IUserRepository;
use App\Repositories\UserProfile\IUserProfileRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserService extends BaseService
{

    protected $userProfileRepo;
    public function __construct(IUserRepository $iUserRepository, IUserProfileRepository $iUserProfileRepository)
    {
        $this->repo = $iUserRepository;
        $this->userProfileRepo = $iUserProfileRepository;
    }



    function login($email, $pass)
    {
        $user = $this->repo->findByEmail($email);

        if ($user && $user->password === md5($pass . $user->salt)) {
            return $user;
        }

        return null;
    }

    public function hashPasswordWithSalt(string $password): array
    {
        $salt = generateRandomString(5);
        return [
            'hashedPassword' => md5($password . $salt),
            'salt'           => $salt,
        ];
    }

    function get()
    {
        return $this->repo->get();
    }

    public function getDetail()
    {
        $user = Auth::user();

        if ($user) {
            return $this->repo->find($user->id);
        }

        return null;
    }

    public function getProfile()
    {
        $user = Auth::user();

        if ($user) {
            return $this->userProfileRepo->getProfile($user->id);
        }

        return null;
    }

    /**
     * Đăng ký tài khoản mới + tạo user_profile trong transaction.
     */
    public function register(array $data): object
    {
        return DB::transaction(function () use ($data) {
            ['hashedPassword' => $hashed, 'salt' => $salt] = $this->hashPasswordWithSalt($data['password']);

            $userId = generateRandomString(10);
            $user   = $this->repo->create([
                'id'             => $userId,
                'name'           => $data['name'],
                'email'          => $data['email'],
                'password'       => $hashed,
                'salt'           => $salt,
                'role'           => 0,
                'account_status' => 1,
                'created_at'     => now(),
            ]);

            $weight = (float) $data['weight'];
            $height = (float) $data['height'];
            $bmi    = round($weight / pow($height / 100, 2), 2);
            $bmiCat = $this->bmiCategory($bmi);

            $this->userProfileRepo->create([
                'id'             => generateRandomString(10),
                'user_id'        => $userId,
                'date_of_birth'  => $data['date_of_birth'],
                'gender'         => $data['gender'],
                'height'         => $height,
                'current_weight' => $weight,
                'bmi'            => $bmi,
                'bmi_category'   => $bmiCat,
                'activity_level' => $data['activity_level'],
                'created_at'     => now(),
'updated_at'     => now(),
            ]);

            return $user;
        });
    }

    /**
     * Cập nhật thông tin hồ sơ của user đang đăng nhập.
     */
    public function updateProfile(array $data): ?object
    {
        $user    = Auth::user();
        $profile = $this->userProfileRepo->getProfile($user->id);

        if (!$profile) {
            return null;
        }

        $updateData = array_filter($data, fn($v) => $v !== null);

        // Tự động tính BMI nếu weight hoặc height thay đổi
        $newWeight = $updateData['current_weight'] ?? $profile->current_weight;
        $newHeight = $updateData['height'] ?? $profile->height;
        if (isset($updateData['current_weight']) || isset($updateData['height'])) {
            $updateData['bmi']          = round((float)$newWeight / pow((float)$newHeight / 100, 2), 2);
            $updateData['bmi_category'] = $this->bmiCategory($updateData['bmi']);
        }

        $this->userProfileRepo->update($profile->id, $updateData);

        return $this->userProfileRepo->getProfile($user->id);
    }

    private function bmiCategory(float $bmi): string
    {
        if ($bmi < 18.5) return 'Underweight';
        if ($bmi < 25.0) return 'Normal';
        if ($bmi < 30.0) return 'Overweight';
        return 'Obese';
    }

}