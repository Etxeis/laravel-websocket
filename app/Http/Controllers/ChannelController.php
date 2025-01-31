<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller; // Asegúrate de importar la clase base Controller

class ChannelController extends Controller
{
    // Agregar un canal a un usuario
    public function addChannel(Request $request)
    {
        $email = $request->input('email');
        $channel = $request->input('channel');

        // Buscar al usuario por email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Agregar el canal a la lista de canales del usuario
        $channels = $user->channels ?? [];
        if (!in_array($channel, $channels)) {
            $channels[] = $channel;
            $user->channels = $channels;
            $user->save();
        }

        return response()->json(['message' => 'Canal agregado correctamente']);
    }

    // Eliminar un canal de un usuario
    public function removeChannel(Request $request)
    {
        $email = $request->input('email');
        $channel = $request->input('channel');

        // Buscar al usuario por email
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Eliminar el canal de la lista de canales del usuario
        $channels = $user->channels ?? [];
        if (($key = array_search($channel, $channels)) !== false) {
            unset($channels[$key]);
            $user->channels = array_values($channels); // Reindexar el array
            $user->save();
        }

        return response()->json(['message' => 'Canal eliminado correctamente']);
    }
}
