<?php

use App\Http\Controllers\CarritoController;
use App\Http\Controllers\PedidoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\VideojuegoController;
use App\Http\Controllers\UsuarioController;

// Ruta para mostrar la página de inicio
Route::get('/', function () {
    return view('home');
})->name('home');

// Ruta para mostrar la lista de videojuegos utilizando el controlador
Route::get('/videojuegos', function () {
    return view('videojuegos.index');
})->name('videojuegos');

// Ruta para mostrar el formulario de login
Route::get('/login', function () {
    return view('usuarios.login');
})->name('login');

// Ruta para enviar los datos del formulario de login y autenticar al usuario
Route::post('/login', [UsuarioController::class, 'login'])->name('login.submit');

// Ruta para cerrar sesión
Route::get('/logout', [UsuarioController::class, 'logout'])->name('logout');

// Ruta para mostrar el formulario de registro
Route::get('/signin', function () {
    return view('registrar.signin');
})->name('signin');

// Ruta para registrar el usuario
Route::post('/signin', [UsuarioController::class, 'registrar'])->name('signin.submit');

// Rutas para el carrito
Route::get('/carrito', [CarritoController::class, 'getCarrito'])->name('carrito');
Route::post('/agregarAlCarrito', [CarritoController::class, 'agregarAlCarrito']);
Route::post('/eliminarDelCarrito', [CarritoController::class, 'eliminarDelCarrito']);
Route::post('/realizarPedido', [PedidoController::class, 'realizarPedido']);

// Ruta para mostrar el videojuego
Route::get('/videojuegos/{id}', [VideojuegoController::class, 'view'])->name('videojuegos.view');

// Ruta para mostrar la página de error
Route::get('/pagerror', function () {
    return view('pagerror');
})->name('pagerror');