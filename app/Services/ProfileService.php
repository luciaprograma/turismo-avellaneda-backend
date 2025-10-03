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
    public function storeProfile($data, $userId)
    {
        // Verificar si el usuario ya tiene perfil
        $profile = $this->profileRepository->findByUserId($userId);

        if ($profile) {
            // Si existe, actualizar
            return $this->profileRepository->update($profile, $data);
        }

        // Si no existe, crear nuevo perfil
        $data['user_id'] = $userId;
        return $this->profileRepository->create($data);
    }
}
