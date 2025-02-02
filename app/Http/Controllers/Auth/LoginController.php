<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle an incoming login request.
     */
    public function login(Request $request)
    {
        // Validar os dados da requisição
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Tentar autenticar o usuário
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Gerar o token para o usuário autenticado
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Login realizado com sucesso!',
                'token' => $token
            ], 200);
        }

        // Se a autenticação falhar
        return response()->json([
            'message' => 'Credenciais inválidas!'
        ], 401);
    }
}

