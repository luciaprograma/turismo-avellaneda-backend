<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $ip = $request->ip();

       
        // CONTROL DE ABUSOS      
        $timeLimit = Carbon::now()->subMinutes(15);

       
        $ipAttempts = DB::table('login_attempts')
            ->where('ip_address', $ip)
            ->where('created_at', '>=', $timeLimit)
            ->count();

        if ($ipAttempts >= 10) {
            return response()->json([
                'message' => 'Demasiados intentos desde esta IP. Intenta más tarde.'
            ], 429);
        }

       
        $emailAttempts = DB::table('login_attempts')
            ->where('email', $email)
            ->where('created_at', '>=', $timeLimit)
            ->count();

        if ($emailAttempts >= 5) {
            return response()->json([
                'message' => 'Demasiados intentos para este correo. Intenta más tarde.'
            ], 429);
        }

       
        $combinedAttempts = DB::table('login_attempts')
            ->where('email', $email)
            ->where('ip_address', $ip)
            ->where('created_at', '>=', $timeLimit)
            ->count();

        if ($combinedAttempts >= 3) {
            return response()->json([
                'message' => 'Demasiados intentos desde esta IP para este correo. Intenta más tarde.'
            ], 429);
        }

      
        // AUTENTICACIÓN
                try {
            $request->authenticate(); 
        } catch (\Throwable $e) {
            
            DB::table('login_attempts')->insert([
                'email' => $email,
                'ip_address' => $ip,
                'created_at' => Carbon::now(),
            ]);

           
            return response()->json([
                'message' => 'Correo o contraseña incorrectas.'
            ], 401);
        }

       
        // EMAIL NO VERIFICADO       
       // EMAIL NO VERIFICADO       
        $user = User::where('email', $email)->first();
        if ($user && ! $user->hasVerifiedEmail()) {
            Auth::guard('web')->logout(); // por si se generó sesión
            return response()->json([
                'message' => 'Tu correo no está verificado. Dirígete al panel de Verificar Correo para reenviar el link.',
                'reason' => 'email_unverified'
            ], 403);
        }

   
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login exitoso'
        ], 200);
    }

    /**
     * Logout del usuario.
     */
    public function destroy(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out'], 200);
    }
}
