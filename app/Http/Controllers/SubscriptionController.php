<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\VentasChannelSubscribed;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;

class SubscriptionController extends Controller
{
    public function subscribeToVentas(Request $request)
    {
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $token);

        try {
            $user = JWTAuth::setToken($token)->authenticate();
            Log::info('Usuario autenticado:', ['user' => $user]);

            // Asegúrate de que $channels sea un array, incluso si está vacío
            $channels = $user->channels ?? [];
            Log::info('Canales del usuario:', ['channels' => $channels]);

            if (!in_array('ventas', $channels)) {
                return response()->json(['error' => 'You are not authorized to access this channel'], 403);
            }

            broadcast(new VentasChannelSubscribed($user));

            return response()->json(['message' => 'Successfully subscribed to ventas channel']);

        } catch (\Exception $e) {
            Log::error('Error en subscribeToVentas:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }
}
