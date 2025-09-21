<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailFrontend extends BaseVerifyEmail
{
    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable)
    {
        $signedUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );

        // Extraer TODOS los parámetros (tanto del path como del query)
        $urlParts = parse_url($signedUrl);
        
        // Obtener id y hash del path: /api/email/verify/{id}/{hash}
        $pathSegments = explode('/', $urlParts['path']);
        $id = $pathSegments[4] ?? null; // posición del id
        $hash = $pathSegments[5] ?? null; // posición del hash
        
        // Obtener expires y signature del query
        $queryParams = [];
        if (isset($urlParts['query'])) {
            parse_str($urlParts['query'], $queryParams);
        }
        
        // Crear todos los parámetros para el frontend
        $allParams = [
            'id' => $id,
            'hash' => $hash,
            'expires' => $queryParams['expires'] ?? null,
            'signature' => $queryParams['signature'] ?? null,
        ];
        
        // Filtrar parámetros null y crear query string
        $allParams = array_filter($allParams, fn($value) => $value !== null);
        $finalQuery = http_build_query($allParams);

        // URL del frontend
        $frontendUrl = 'http://localhost:5173/email-verified';

        return $frontendUrl . '?' . $finalQuery;
    }

    public function toMail($notifiable)
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifica tu correo electrónico')
            ->line('¡Gracias por registrarte! Por favor, verifica tu correo haciendo clic en el siguiente botón.')
            ->action('Verificar Correo', $url)
            ->line('Si no creaste esta cuenta, podés ignorar este mensaje.');
    }
}