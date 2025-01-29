<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('ventas', function ($user) {
    // Aquí se puede agregar la lógica para permitir el acceso a los usuarios
    // que estén autorizados a suscribirse al canal 'ventas'
    // Verificamos que el JWT incluya 'ventas' en el campo 'channels'
    return in_array('ventas', $user->channels);
});
