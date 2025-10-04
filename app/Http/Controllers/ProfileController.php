<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;


class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    //---------------------- Ver datos del perfil ---------------------------
   public function show()
{
    $userId = Auth::id();
     if (!$userId) {
        Log::error("Usuario no autenticado intentando acceder al perfil");
        return response()->json([
            'status' => 'error',
            'message' => 'Usuario no autenticado'
        ], 401);
    }
    try {
        $profile = $this->profileService->getProfileByUserId($userId);

        
        if (!$profile) {
            $profile = new \App\Models\Profile();
        }

        return response()->json([
            'status' => 'ok',
            'data' => $profile
        ], 200);

    } catch (\Exception $e) {
        Log::error("Error al obtener perfil", [
            'user_id' => $userId,
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'No se pudo obtener el perfil'
        ], 500);
    }
}


    //------------------------ Guardar perfil (crear o actualizar) ------------------------
   public function update(Request $request)
{
    $userId = Auth::id();
    Log::info("=== INICIO UPDATE ===", ['user_id' => $userId]);

    try {
        $profile = $this->profileService->getProfileByUserId($userId);
        Log::info("Perfil encontrado", ['profile' => $profile]);
        
        if (!$profile) {
            return response()->json(['status' => 'error', 'message' => 'Perfil no encontrado'], 404);
        }

        Log::info("Iniciando validación");
        
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
            'dni' => ['required', 'digits_between:7,15', Rule::unique('profiles')->ignore($profile->id)],
            'birth_date' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'address' => ['required', 'string', 'max:255'],
            'phone_country_code' => ['required', 'string', 'regex:/^\+\d{1,3}$/'],
            'phone_area_code' => ['required', 'string', 'regex:/^\d{1,5}$/'],
            'phone_number' => ['required', 'string', 'regex:/^\d{6,10}$/'],
            'emergency_country_code' => ['nullable', 'string', 'regex:/^\+\d{1,3}$/'],
            'emergency_area_code' => ['nullable', 'string', 'regex:/^\d{1,5}$/'],
            'emergency_number' => ['nullable', 'string', 'regex:/^\d{6,10}$/'],
        ]);

        Log::info("Validación exitosa, llamando a updateProfile");

        $profile = $this->profileService->updateProfile($validated, $userId);

        Log::info("Update exitoso");

        return response()->json(['status' => 'ok', 'data' => $profile], 200);

    } catch (ValidationException $e) {
        Log::error("Error de validación", ['errors' => $e->errors()]);
        throw $e;
    } catch (\Exception $e) {
        Log::error("ERROR CRÍTICO EN UPDATE", [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}
}
