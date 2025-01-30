<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Middleware\CheckTokenVersion;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ChannelController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::middleware([CheckTokenVersion::class])->group(function () {
    Route::controller(TodoController::class)->group(function () {
        Route::get('todos', 'index');
        Route::post('todo', 'store');
        Route::get('todo/{id}', 'show');
        Route::put('todo/{id}', 'update');
        Route::delete('todo/{id}', 'destroy');
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json([
        'nombre' => $request->user()->name,
        'correo' => $request->user()->email
    ]);
});

Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->post('/subscribe/ventas', [SubscriptionController::class, 'subscribeToVentas']);

// Agregar un canal a un usuario
Route::post('/user/add-channel', [ChannelController::class, 'addChannel']);

// Eliminar un canal de un usuario
Route::post('/user/remove-channel', [ChannelController::class, 'removeChannel']);
