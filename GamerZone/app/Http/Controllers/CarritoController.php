<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Http\Controllers\UsuarioController;
use App\Models\Videojuego;

class CarritoController extends Controller
{
    //obtiene el videojuego en el carrito
    public function getCarrito(Request $request) {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $token = UsuarioController::comprobarToken();
        if(!$token) {
            UsuarioController::logout();
            return redirect('/login');
        }
        $carrito = $_SESSION['carrito'];
        return view('carrito.carrito', ['carrito' => $carrito]);
    }

        // Agrega un videojuego al carrito
    public function agregarAlCarrito(Request $request) {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['usuario'])) {
            return response()->json(['sesion' => false]);
        }

        $token = UsuarioController::comprobarToken();
        if(!$token) {
            UsuarioController::logout();
            return response()->json(['exito' => false]);
        }

        $carrito = $_SESSION['carrito'];
        $productoId = intval($request->input('id'));
        $productoYaEnCarrito = false;
    
        foreach ($carrito as $indice => $producto) {
            if ($producto['id'] == $productoId) {
                $productoYaEnCarrito = true;
                $carrito[$indice]['cantidad']++;
                break;
            }
        }
    
        if (!$productoYaEnCarrito) {
            $videojuego = Videojuego::where('id', $productoId)->first();
            $carrito[] = [
                'id' => $videojuego->id,
                'nombre' => $videojuego->nombre,
                'portada' => $videojuego->portada,
                'precio' => $videojuego->precio,
                'cantidad' => 1
            ];
        }
    
        $_SESSION['carrito'] = $carrito;
        return response()->json(['exito' => true]);
    }

    // Elimina un producto del carrito
    public function eliminarDelCarrito(Request $request) {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $token = UsuarioController::comprobarToken();
        if(!$token) {
            UsuarioController::logout();
            return redirect('/login');
        }
        $carrito = $_SESSION['carrito'];
        $productoId = intval($request->input('producto_id'));
        foreach ($carrito as $indice => $producto) {
            if ($producto['id'] == $productoId) {
                unset($carrito[$indice]);
                break;
            }
        }
    
        $_SESSION['carrito'] = $carrito;
        return redirect('/carrito');
    }
} 