<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }
//------------------------GUARDAR PERFIL (CREAR O ACTUALIZAR)------------------------
   public function store(Request $request)
    {
        $userId = Auth::id(); 

        Log::info("Request recibido para crear/actualizar perfil", ['user_id' => $userId]);

        try {
           
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
                'last_name' => ['required', 'string', 'max:100', 'regex:/^[\pL\s\'-]+$/u'],
                'dni' => ['required', 'digits_between:7,15', 'unique:profiles,dni'],
                'birth_date' => ['required', 'date', 'before:today', 'after:1900-01-01'],
                'address' => ['required', 'string', 'max:255'],

              
                'country_code' => ['required', 'string', 'regex:/^\+\d{1,3}$/'],
                'area_code' => ['required', 'string', 'regex:/^\d{1,5}$/'],
                'number' => ['required', 'string', 'regex:/^\d{6,10}$/'],

             
                'emergency_country_code' => ['required', 'string', 'regex:/^\+\d{1,3}$/'],
                'emergency_area_code' => ['required', 'string', 'regex:/^\d{1,5}$/'],
                'emergency_number' => ['required', 'string', 'regex:/^\d{6,10}$/'],
            ]);

            
            $fullPhone = $validated['country_code'].'9'.$validated['area_code'].$validated['number'];
            $fullEmergencyPhone = $validated['emergency_country_code'].'9'.$validated['emergency_area_code'].$validated['emergency_number'];

            // Guardar perfil con datos validados
            $profile = $this->profileService->storeProfile([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'dni' => $validated['dni'],
                'birth_date' => $validated['birth_date'],
                'address' => $validated['address'],
                'phone_number' => $fullPhone,
                'emergency_contact' => $fullEmergencyPhone,
            ], $userId);

            Log::info("Perfil procesado correctamente", ['profile_id' => $profile->id]);

            return response()->json(['status' => 'ok', 'data' => $profile], 201);

        } catch (ValidationException $e) {
            
            foreach ($e->errors() as $field => $messages) {
                Log::warning("Error de validación en perfil", [
                    'user_id' => $userId,
                    'field' => $field,
                    'value' => $request->input($field),
                    'messages' => $messages,
                ]);
            }
            throw $e; 
        } catch (\Exception $e) {
           
            Log::error("Error inesperado al procesar perfil", [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['status' => 'error', 'message' => 'No se pudo guardar el perfil'], 500);
        }
    }

}
