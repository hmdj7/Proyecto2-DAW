<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Http\Controllers\UsuarioController;
use App\Models\BD;
use App\Models\Videojuego_Pedido;

use PDO;

class PedidoController extends Controller
{

    //realiza el pedido
    public function realizarPedido(Request $request) {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        if (!isset($_SESSION['carrito'])) {
            return redirect()->route('home');
        }

        $token = UsuarioController::comprobarToken();
        if(!$token) {
            UsuarioController::logout();
            return redirect('/login');
        }
    
        $carrito = $_SESSION['carrito'];
        $total = 0;
    
        foreach ($carrito as $producto) {
            $total += $producto['cantidad'] * $producto['precio'];
        }
    
        $bd = new BD();
        $conn = $bd->conectarBD();

        $query = $conn->prepare("INSERT INTO pedidos (id_usuario, fecha_pedido, total) VALUES (:id, :fecha, :total)");
        $query->execute([
            'id' => $_SESSION['id'],
            'fecha' => date('Y-m-d H:i:s'),
            'total' => $total
        ]);

        $pedidoId = $conn->lastInsertId();
    
        foreach ($carrito as $producto) {
            $query = $conn->prepare("INSERT INTO videojuegos_pedidos (id_videojuego, id_pedido, cantidad) VALUES (:id, :id_pedido, :cantidad)");
            $query->execute([
                'id' => $producto['id'],
                'id_pedido' => $pedidoId,
                'cantidad' => $producto['cantidad']
            ]);
        }

        $carritoUsuario = $conn->prepare("SELECT * FROM carritos WHERE id_usuario = :id");
        $carritoUsuario->bindParam(':id', $_SESSION['id']);
        $carritoUsuario->execute();
        $carrito = $carritoUsuario->fetch(PDO::FETCH_OBJ);

        $query = $conn->prepare("DELETE FROM videojuegos_carritos WHERE id_carrito = :id");
        $query->bindParam(':id', $carrito->id);
        $query->execute();

        $bd->cerrarBD();
    
        $_SESSION['carrito'] = [];
    
        return redirect()->route('carrito');
    }
    
}