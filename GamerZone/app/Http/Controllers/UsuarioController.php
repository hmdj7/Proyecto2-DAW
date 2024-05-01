<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\BD;

use PDO;

class UsuarioController extends Controller
{
    // Mostrar el formulario de login
    public function index()
    {
        return view('usuarios.login');
    }

    // Enviar los datos del formulario de login y autenticar al usuario
    public function login(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'usuario' => 'required|exists:App\Models\Usuario,usuario',
            'contraseña' => 'required|exists:App\Models\Usuario,contraseña',
        ], [
            'usuario.required' => 'El campo usuario es obligatorio.',
            'usuario.exists' => 'El usuario ingresado no existe.',
            'contraseña.required' => 'El campo contraseña es obligatorio.',
        ]);
        
    
        $usuario = $request->input('usuario');
        $contraseña = $request->input('contraseña');
    
        $bd = new BD();
        $conn = $bd->conectarBD();
    
        $query = $conn->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $query->bindParam(':usuario', $usuario);
        $query->execute();
        $usuario = $query->fetch(PDO::FETCH_OBJ);
    
        if ($usuario) {
            if ($contraseña == $usuario->contraseña) {
                session_start();
                $_SESSION['usuario'] = $request->input('usuario');
                $_SESSION['id'] = $usuario->id;
                $_SESSION['status'] = 'logueado';
                
                $fecha = date('Y-m-d H:i:s');
                $query = $conn->prepare("SELECT * FROM usuariotoken WHERE id_usuario = :id AND fechavencimiento > :fecha");
                $query->bindParam(':id', $usuario->id);
                $query->bindParam(':fecha', $fecha);
                $query->execute();
                $tokenUsuario = $query->fetch(PDO::FETCH_OBJ);

                if($tokenUsuario) {
                    $_SESSION['token'] = $tokenUsuario->token;
                }else{
                    $nuevoToken = bin2hex(random_bytes(32));
                    $query = $conn->prepare("INSERT INTO usuariotoken (id_usuario, token, fechainicio, fechavencimiento) VALUES (:id, :token, :fechainicio, :fechavencimiento)");
                    $query->execute([
                        'id' => $usuario->id,
                        'token' => $nuevoToken,
                        'fechainicio' => date('Y-m-d H:i:s'),
                        'fechavencimiento' => date('Y-m-d H:i:s', strtotime('+90 minutes')),
                    ]);
                    $token = $query->fetch(PDO::FETCH_OBJ);
                    $_SESSION['token'] = $nuevoToken;
                }
                $query = $conn->prepare("SELECT * FROM carritos WHERE id_usuario = :id");
                $query->bindParam(':id', $usuario->id);
                $query->execute();
                $carritoUsuario = $query->fetch(PDO::FETCH_OBJ);
    
                // Obtener productos del carrito
                if ($carritoUsuario) {
                    $query = $conn->prepare("SELECT * FROM videojuegos_carritos WHERE id_carrito = :id_carrito");
                    $query->bindParam(':id_carrito', $carritoUsuario->id);
                    $query->execute();
                    $productos_carrito = $query->fetchAll(PDO::FETCH_ASSOC);
    
                    // Almacenar productos en la sesión
                    $_SESSION['carrito'] = [];
                    foreach ($productos_carrito as $producto) {
                        $query = $conn->prepare("SELECT * FROM videojuegos WHERE id = :id");
                        $query->bindParam(':id', $producto['id_videojuego']);
                        $query->execute();
                        $videojuego = $query->fetch(PDO::FETCH_OBJ);
                        $_SESSION['carrito'][] = [
                            'id' => $videojuego->id,
                            'nombre' => $videojuego->nombre,
                            'portada' => $videojuego->portada,
                            'precio' => $videojuego->precio,
                            'cantidad' => $producto['cantidad']
                        ];
                    }
                } else {
                    $_SESSION['carrito'] = [];
                }
    
                return redirect()->route('home');
            } else {
                return back()->withInput()->withErrors(['error' => 'Contraseña incorrecta']);
            }
        } else {
            // Usuario no encontrado
            return back()->withInput()->withErrors(['error' => 'Usuario no encontrado']);
        }
    }
    
    // Comprobar si el token es válido
    public static function comprobarToken(){
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $bd = new BD();
        $conn = $bd->conectarBD();

        $fecha = date('Y-m-d H:i:s');
        $query = $conn->prepare("SELECT * FROM usuariotoken WHERE id_usuario = :id AND token = :token AND fechavencimiento > :fecha");
        $query->bindParam(':id', $_SESSION['id']);
        $query->bindParam(':token', $_SESSION['token']);
        $query->bindParam(':fecha', $fecha);
        $query->execute();
        $token = $query->fetch(PDO::FETCH_OBJ);

        if($token) {
            return true;
        }else{
            return false;
        }
    }
    
    // Cerrar sesión
    public static function logout()
    {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $bd = new BD();
        $conn = $bd->conectarBD();

        $query = $conn->prepare("SELECT * FROM carritos WHERE id_usuario = :id");
        $query->bindParam(':id', $_SESSION['id']);
        $query->execute();

        $carrito = $query->fetch(PDO::FETCH_OBJ);

        if (!$carrito) {
            $query = $conn->prepare("INSERT INTO carritos (id_usuario) VALUES (:id)");
            $query->bindParam(':id', $_SESSION['id']);
            $query->execute();

            $query = $conn->prepare("SELECT * FROM carritos WHERE id_usuario = :id");
            $query->bindParam(':id', $_SESSION['id']);
            $query->execute();
            $carrito = $query->fetch(PDO::FETCH_OBJ);
        }

        $query = $conn->prepare("DELETE FROM videojuegos_carritos WHERE id_carrito = :id_carrito");
        $query->bindParam(':id_carrito', $carrito->id);
        $query->execute();
        $carritoId = $carrito->id;

        if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
            foreach ($_SESSION['carrito'] as $producto) {
                $query = $conn->prepare("INSERT INTO videojuegos_carritos (id_videojuego, id_carrito, cantidad) VALUES (:id_videojuego, :id_carrito, :cantidad)");
                $query->bindParam(':id_videojuego', $producto['id']);
                $query->bindParam(':id_carrito', $carritoId);
                $query->bindParam(':cantidad', $producto['cantidad']);
                $query->execute();
            }
        }
        session_destroy();
        $ruta = request()->headers->get('referer');
        if(strpos($ruta, 'carrito') !== false) {
            return redirect('/login');
        }
        return redirect()->route('home')->with('status', 'Sesión cerrada correctamente');
    }

    // Registrar usuario
    public function registrar(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:App\Models\Usuario,email',
            'usuario' => 'required|min:4|max:20|unique:App\Models\Usuario,usuario',
            'contraseña' => 'required|min:8|max:20',
        ], [
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'El formato del email es inválido.',
            'email.unique' => 'Este email ya está registrado.',
        
            'usuario.required' => 'El campo usuario es obligatorio.',
            'usuario.min' => 'El usuario debe tener al menos :min caracteres.',
            'usuario.max' => 'El usuario no puede tener más de :max caracteres.',
            'usuario.unique' => 'Este usuario ya está en uso.',
        
            'contraseña.required' => 'El campo contraseña es obligatorio.',
            'contraseña.min' => 'La contraseña debe tener al menos :min caracteres.',
            'contraseña.max' => 'La contraseña no puede tener más de :max caracteres.',
        ]);
        
    
        $bd = new BD();
        $conn = $bd->conectarBD();
    
        $query = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $query->execute(['email' => $request->input('email')]);
        $usuario_existente = $query->fetch(PDO::FETCH_OBJ);
    
        if ($usuario_existente) {
            return back()->withInput()->withErrors(['error' => 'Email ya registrado']);
        } else {
            $usuario = new Usuario();
            $usuario->email = $request->input('email');
            $usuario->usuario = $request->input('usuario');
            $usuario->contraseña = $request->input('contraseña');
            $usuario->save();
            
            $nuevo_usuario_id = $usuario->id;
    
            // Iniciar sesión y guardar información del usuario
            session_start();
            $_SESSION['id'] = $nuevo_usuario_id;
            $_SESSION['usuario'] = $request->input('usuario');
            $_SESSION['carrito'] = [];
            $_SESSION['status'] = 'logueado';
            $bd->cerrarBD();
    
            return redirect()->route('home');
        }
    }
    
}