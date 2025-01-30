<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

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
