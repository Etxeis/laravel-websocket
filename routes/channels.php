<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('ventas', function ($user) {
    \Log::info('Intentando autenticar usuario en canal ventas', ['user' => $user]);

    if (!$user) {
        \Log::error('No se pudo autenticar el usuario en broadcasting/auth');
        return false;
    }

    return in_array('ventas', $user->channels ?? []);
});

Broadcast::channel('ventas', function ($user) {
    \Log::info('User Authenticated:', ['user' => $user]); // Verificar si Laravel detecta al usuario
    return true;
});
