<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CheckTokenVersion
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Obtener el token JWT
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();

            // Obtener el usuario autenticado
            $user = auth()->user();

            // Verificar la versión del token
            Log::info('Payload Token version: ' . $payload->get('token_version'));
            Log::info('DB Token version: ' . $user->token_version);

            if ($payload->get('token_version') !== $user->token_version) {
                return response()->json(['error' => 'Token has been invalidated'], 401);
            }

            // Verificar que el campo 'channels' exista en el payload
            if (!$payload->has('channels')) {
                return response()->json(['error' => 'The token does not contain the "channels" field'], 401);
            }

            // Obtener los canales del payload
            $channels = $payload->get('channels');

            // Verificar que 'channels' sea un array y no esté vacío
            if (!is_array($channels) || empty($channels)) {
                return response()->json(['error' => 'The "channels" field is invalid or empty'], 401);
            }

            // Verificar si el token ha expirado
            if (JWTAuth::parseToken()->isExpired()) {
                return response()->json(['error' => 'The token has expired'], 401);
            }

        } catch (\Exception $e) {
            // Manejo de excepciones: si el token es inválido o algo sale mal
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Si todas las validaciones son correctas, continuar con la solicitud
        return $next($request);
    }
}
