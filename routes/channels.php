<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('ventas', function () {
    return true; // Permitir acceso a todos los usuarios sin autenticación
});
