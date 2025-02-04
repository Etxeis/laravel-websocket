<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            // Validar la entrada
            $validated = $request->validate([
                'message' => 'required|string',
            ]);

            // Verificar si el usuario está autenticado
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            // Emitir el evento
            broadcast(new MessageSent($user, $validated['message']))->toOthers();

            return response()->json(['message' => 'Mensaje enviado correctamente'], 200);

        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}
