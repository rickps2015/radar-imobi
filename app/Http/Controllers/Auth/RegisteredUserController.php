<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;  // Importando JsonResponse
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Notifications\UserNotification;
use Exception;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse  // Alterando para JsonResponse
    {
        try {
            // Validação dos dados de entrada
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // Criação do usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Disparar evento de registro
            event(new Registered($user));

            // Logar o usuário após o registro (opcional)
            Auth::login($user);

            // Enviar notificação ao novo usuário
            $user->notify(new UserNotification());

            // Retornar resposta com mensagem de sucesso
            return response()->json([
                'message' => 'Usuário criado com sucesso!',
                'user' => $user
            ], 201);  // Retorna um JSON com status 201

        } catch (Exception $e) {
            // Caso ocorra algum erro, retornar o erro
            return response()->json([
                'message' => 'Erro ao registrar o usuário.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}



