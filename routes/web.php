<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', function () {
    return view('register');
});

Route::get('/login', function () {
    return view('login');
});


// Ruta para la página de inicio
Route::get('/home', function (Request $request) {
    $user = $request->query('user'); // Recuperar el usuario de la query string

    if (!$user) {
        return redirect('/login'); // Redirigir si no hay usuario
    }

    $user = json_decode($user); // Decodificar el JSON recibido
    return view('home', ['user' => $user]);
})->name('home');
