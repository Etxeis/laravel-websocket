<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Events\MessageSent;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/home', function (Request $request) {
    return view('home')->with([
        'nombre' => Auth::user()->name ?? 'Invitado',
        'correo' => Auth::user()->email ?? 'Sin correo'
    ]);
});

Route::get('/channelAdmin', function () {
    return view('channelAdmin');
});

Route::get('/ws', function () {
    return view('test');
});

Route::post('/send-message', function (Request $request) {
    event(new MessageSent($request->message));
    return null;
});

