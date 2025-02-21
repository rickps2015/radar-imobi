<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class NewPasswordController extends Controller
{
    /**
     * Envia um código de 6 dígitos para o e-mail do usuário.
     */
    public function sendResetCode(Request $request)
    {
        // Valida a entrada para garantir que o e-mail foi fornecido e é válido
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Verifica se o e-mail existe no banco de dados
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        // Gera um código aleatório de 6 dígitos
        $resetCode = Str::random(6);

        // Armazena o código gerado no cache, com um tempo de expiração (10 minutos por exemplo)
        Cache::put('reset_code_' . $user->email, $resetCode, 600); // 600 segundos = 10 minutos

        // Envia o código por e-mail
        Mail::send('emails.reset_password', ['resetCode' => $resetCode], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Código de Redefinição de Senha');
        });

        return response()->json(['message' => 'Código de redefinição enviado com sucesso.']);
    }

    /**
     * Verifica o código recebido e altera a senha do usuário.
     */
    public function verifyResetCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'reset_code' => ['required', 'string'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Verifica se o e-mail está associado a um usuário
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        // Recupera o código armazenado no cache
        $storedCode = Cache::get('reset_code_' . $user->email);

        // Verifica se o código fornecido pelo usuário é válido
        if ($storedCode !== $request->reset_code) {
            return response()->json(['message' => 'Código inválido.'], 400);
        }

        // Atualiza a senha do usuário
        $user->password = Hash::make($request->password);
        $user->save();

        // Limpa o código do cache
        Cache::forget('reset_code_' . $user->email);

        return response()->json(['message' => 'Senha redefinida com sucesso.']);
    }

    /**
     * Verifica se o código de redefinição é válido.
     */
    public function checkResetCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'reset_code' => ['required', 'string'],
        ]);

        // Verifica se o e-mail está associado a um usuário
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        // Recupera o código armazenado no cache
        $storedCode = Cache::get('reset_code_' . $user->email);

        // Verifica se o código fornecido pelo usuário é válido
        if ($storedCode !== $request->reset_code) {
            return response()->json(['message' => 'Código inválido.'], 400);
        }

        return response()->json(['message' => 'Código válido.']);
    }
}

