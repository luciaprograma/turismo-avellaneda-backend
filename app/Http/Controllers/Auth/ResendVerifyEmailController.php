<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Notifications\VerifyEmailFrontend;

class ResendVerifyEmailController extends Controller
{
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        $genericResponse = response()->json([
            'message' => 'Si tu correo está registrado y pendiente de verificación, recibirás un nuevo link.'
        ]);

        if (!$user || $user->hasVerifiedEmail()) {
            return $genericResponse;
        }

        // Limite por usuario
        $timeLimit = Carbon::now()->subMinutes(15);
        $resendCount = DB::table('email_resend_logs')
            ->where('user_id', $user->id)
            ->where('requested_at', '>=', $timeLimit)
            ->count();

        if ($resendCount >= 3) {
            return response()->json([
                'message' => 'Has alcanzado el límite de reenvíos. Intenta más tarde.'
            ], 429);
        }

        // Limite por IP
        $ipLimitTime = Carbon::now()->subHour();
        $ipResendCount = DB::table('email_resend_logs')
            ->where('ip_address', $request->ip())
            ->where('requested_at', '>=', $ipLimitTime)
            ->count();

        if ($ipResendCount >= 10) {
            return response()->json([
                'message' => 'Demasiados intentos desde esta IP. Intenta más tarde.'
            ], 429);
        }

        DB::table('email_resend_logs')->insert([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'requested_at' => Carbon::now(),
        ]);

        $user->notify(new VerifyEmailFrontend());

        return $genericResponse;
    }
}
