<?php

namespace App\Services;

use App\Repositories\ProfileRepository;

class ProfileService
{
    protected $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function storeProfile(array $data, int $userId)
    {
       
        // 1. si el usuario ya tiene perfil, actualizar
      
        $profile = $this->profileRepository->findByUserId($userId);
          // 2. si no tiene, crear uno nuevo
        if ($profile) {
            return $this->profileRepository->update($profile, $data);
        }

        return $this->profileRepository->create($data + ['user_id' => $userId]);
    }
}
