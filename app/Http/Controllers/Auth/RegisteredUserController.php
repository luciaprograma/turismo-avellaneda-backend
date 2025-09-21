<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        
        $email = strtolower($validated['email']);

  
        $user = User::create([
            'email' => $email,
            'password' => Hash::make($validated['password']),
        ]);

       
        event(new Registered($user));

      
        return response()->json([
            'message' => 'Usuario creado. Revisá tu correo para verificar el email.'
        ], 201);
    }
}
