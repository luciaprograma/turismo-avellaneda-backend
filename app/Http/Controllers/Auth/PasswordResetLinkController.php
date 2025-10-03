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

        Log::info('Password reset request', [
            'email' => $request->input('email'),
            'status' => $status,
        ]);

        
    
    Log::info('Password reset link status', ['status' => $status]);
       usleep(random_int(450000, 650000)); 
        
        return response()->json([
            'status' => 'ok',
            'message' => 'Se envió un link al correo registrado.'
        ]); 
    }
}
