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

    //---------------------- Ver datos del perfil ---------------------------
    public function getProfileByUserId($userId)
    {
        return $this->profileRepository->findByUserId($userId);
    }

    //------------------------ Guardar perfil (crear o actualizar) ------------------------
    public function createProfile($data, $userId) {
    $data['user_id'] = $userId;
    return $this->profileRepository->create($data);
}

public function updateProfile($data, $userId) {
    $profile = $this->profileRepository->findByUserId($userId);
    if (!$profile) {
        throw new \Exception("Perfil no existe para actualizar");
    }
    return $this->profileRepository->update($profile, $data);
}

}
