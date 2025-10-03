<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ApiVerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::info('=== INICIO VERIFICACIÓN DE EMAIL ===');

        $userId = $request->route('id');
        $hash = $request->route('hash');

        Log::info('Parámetros recibidos:', [
            'id' => $userId,
            'hash' => $hash,
            'expires' => $request->get('expires'),
            'signature' => $request->get('signature'),
        ]);

        if (! $request->hasValidSignature()) {
            Log::error('Firma inválida o expirada');
            return response()->json([
                'message' => 'Verificación fallida. Firma inválida o expirada.'
            ], 400);
        }

        Log::info('Firma válida ');

        $user = User::find($userId);
        if (! $user) {
            Log::error('Usuario no encontrado', ['user_id' => $userId]);
            return response()->json([
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        Log::info('Usuario encontrado ✓', ['user_id' => $user->id]);

        $expectedHash = sha1($user->getEmailForVerification());
        if ($hash !== $expectedHash) {
            Log::error('Hash no coincide', [
                'provided_hash' => $hash,
                'expected_hash' => $expectedHash
            ]);
            return response()->json([
                'message' => 'Hash de verificación inválido.'
            ], 400);
        }

        Log::info('Hash válido ✓');

        if (! $user->hasVerifiedEmail()) {
            Log::info('Marcando email como verificado');
            $user->markEmailAsVerified();
        } else {
            Log::info('Email ya estaba verificado');
        }

        Log::info('=== VERIFICACIÓN EXITOSA ===');

        return response()->json([
            'message' => 'Correo verificado con éxito. En unos segundos serás redirigido a la página principal.'
        ]);
    }
}
