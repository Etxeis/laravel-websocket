<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class CheckTokenAndChannel
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Obtener el token JWT
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();

            // Verificar que el canal esté incluido en el payload
            $channels = $payload->get('channels');
            $channelRequested = $request->route('channel'); // Canal solicitado

            if (!in_array($channelRequested, $channels)) {
                return response()->json(['error' => 'You do not have access to this channel'], 401);
            }

            // Continuar con la solicitud si es válida
            return $next($request);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }
}
