<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\VentasChannelSubscribed;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Routing\Controller;

class SubscriptionController extends Controller
{
    public function subscribeToVentas(Request $request)
    {
        // Obtener el token del cliente (se asume que el cliente lo envía en los headers)
        $token = $request->header('Authorization'); // Asegurar que el cliente pase el token
        $token = str_replace('Bearer ', '', $token); // Eliminar "Bearer " si está presente

        try {
            // Parsear el token JWT
            $user = JWTAuth::setToken($token)->authenticate();

            // Validar que el token esté bien y verificar el canal en el payload
            $channels = $user->channels;
            if (!in_array('ventas', $channels)) {
                return response()->json(['error' => 'You are not authorized to access this channel'], 403);
            }

            // Emitir el evento que el usuario se ha suscrito al canal 'ventas'
            broadcast(new VentasChannelSubscribed($user));

            return response()->json(['message' => 'Successfully subscribed to ventas channel']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }
}
