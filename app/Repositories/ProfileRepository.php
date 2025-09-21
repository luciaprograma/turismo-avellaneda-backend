<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class ProfileRepository
{
    //-------------------------- Buscar perfil por user_id----------------------------
    public function findByUserId(int $userId): ?Profile
    {
        $profile = Profile::where('user_id', $userId)->first();

        if ($profile) {
            Log::info("Perfil encontrado para user_id={$userId}", ['profile_id' => $profile->id]);
        } else {
            Log::info("No se encontró perfil para user_id={$userId}");
        }

        return $profile;
    }

    //--------------------------- Crear nuevo perfil con validación de email y rollback-------------------------------
    public function create(array $data): Profile
    {
        return DB::transaction(function () use ($data) {

            // Verificar que el usuario existe y tiene email verificado
            $user = User::findOrFail($data['user_id']);
            if (is_null($user->email_verified_at)) {
                throw new Exception("El usuario no tiene el email verificado, no se puede crear el perfil.");
            }

            $profile = Profile::create($data);

            Log::info("Perfil creado correctamente", [
                'user_id'   => $data['user_id'],
                'profile_id'=> $profile->id,
            ]);

            return $profile;
        });
    }

    //----------------------------- Actualizar perfil existente con validación de email y rollback-----------------------
    public function update(Profile $profile, array $data): Profile
    {
        return DB::transaction(function () use ($profile, $data) {

            // Verificar que el usuario existe y tiene email verificado
            $user = $profile->user;
            if (is_null($user->email_verified_at)) {
                throw new Exception("El usuario no tiene el email verificado, no se puede actualizar el perfil.");
            }

            $profile->update($data);

            Log::info("Perfil actualizado correctamente", [
                'profile_id' => $profile->id,
                'user_id'    => $profile->user_id,
            ]);

            return $profile;
        });
    }
}
