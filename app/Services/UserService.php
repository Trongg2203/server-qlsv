<?php

namespace App\Services;

use App\Repositories\User\IUserRepository;
use App\Repositories\UserProfile\IUserProfileRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserService extends BaseService
{

    protected $userProfileRepo;
    protected $user;
    public function __construct(IUserRepository $iUserRepository, IUserProfileRepository $iUserProfileRepository)
    {
        $this->repo = $iUserRepository;
        $this->userProfileRepo = $iUserProfileRepository;
        $this->user = $iUserRepository;
    }



    function login($email, $pass)
    {

        $data = $this->repo->findByEmailAndPassword($email, $pass);
        return $data;
    }

    function findByEmail($data)
    {
        $data = $this->repo->findByEmail($data['email']);
        return $data;
    }

    public function createUser($data)
    {
        if (!isset($data['password'])) {
            return null;
        }

        $data['password'] = Hash::make($data['password']);

        return $this->repo->create($data);
    }

    function getAllActive()
    {
        return $this->repo->getAllActive();
    }

    public function getDetail()
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            return $this->repo->find($user->id);
        }

        return null;
    }

    public function getMyProfile()
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            return $this->userProfileRepo->getMyProfile();
        }

        return null;
    }

    public function updateMyProfile(array $data)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return null;
        }

        $profile = $this->userProfileRepo->getMyProfile();
        if (!$profile) {
            return null;
        }

        if (array_key_exists('height', $data) || array_key_exists('current_weight', $data)) {
            $height = $data['height'] ?? $profile->height;
            $weight = $data['current_weight'] ?? $profile->current_weight;
            $bmiData = $this->calculateBmiData($height, $weight);
            if ($bmiData) {
                $data = array_merge($data, $bmiData);
            }
        }

        return $this->userProfileRepo->updateMyProfile($data);
    }

    public function createMyProfile(array $data)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return null;
        }

        $existing = $this->userProfileRepo->getMyProfile();
        if ($existing) {
            return 'exists';
        }

        $bmiData = $this->calculateBmiData($data['height'], $data['current_weight']);
        if ($bmiData) {
            $data = array_merge($data, $bmiData);
        }

        return $this->userProfileRepo->createMyProfile($data);
    }

    private function calculateBmiData($heightCm, $weightKg): ?array
    {
        $height = (float) $heightCm;
        $weight = (float) $weightKg;

        if ($height <= 0 || $weight <= 0) {
            return null;
        }

        $heightMeters = $height / 100;
        $bmi = $weight / ($heightMeters * $heightMeters);
        $bmiRounded = round($bmi, 2);

        return [
            'bmi' => $bmiRounded,
            'bmi_category' => $this->getBmiCategory($bmiRounded),
        ];
    }

    private function getBmiCategory(float $bmi): string
    {
        if ($bmi < 18.5) {
            return 'Underweight';
        }

        if ($bmi < 25) {
            return 'Normal';
        }

        if ($bmi < 30) {
            return 'Overweight';
        }

        return 'Obese';
    }

    public function changePassword($data)
    {
        return $this->repo->changePassword($data);
    }

    public function get()
    {
        return $this->repo->get();
    }

    public function delete($id): bool
    {
        $user = $this->repo->find($id);
        if ($user) {
            return  $this->user->delete($id);
        }

        return false;
    }
}
