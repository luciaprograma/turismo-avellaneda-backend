<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class PasswordResetLinkController extends Controller
{
    
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
        Log::info('Email recibido en forgot-password: '.$request->email);


        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Guardar en log interno aunque no exista el email
        Log::info('Password reset request', [
            'email' => $request->input('email'),
            'status' => $status,
        ]);

        
    // Loguear el resultado de sendResetLink
    Log::info('Password reset link status', ['status' => $status]);
       usleep(random_int(450000, 650000)); // Retardo random 
        // Siempre la misma respuesta externa
        return response()->json([
            'status' => 'ok',
            'message' => 'Se envió un link al correo registrado.'
        ]); 
    }
}
