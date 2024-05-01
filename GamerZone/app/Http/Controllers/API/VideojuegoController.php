<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Videojuego;
use App\Models\BD;
use Illuminate\Support\Facades\Session;


class VideojuegoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     //Obtiene los videojuegos de la base de datos en orden aleatorio
    public function index(){
        $bd = new BD();
        $conn = $bd->conectarBD();

        $videojuegos = [];

        $query = $conn->prepare("SELECT * FROM Videojuegos ORDER BY RAND()");
        $query->execute();
        $videojuegos = $query->fetchAll();
        $bd->cerrarBD();
        return response()->json($videojuegos);
    }

    //Obtiene un videojuego de la base de datos
    public function show($id)
    {
        return response()->json(Videojuego::find($id));
    }

    //Obtiene un videojuego de la base de datos y lo muestra especificamente a el
    public function view($id)
    {
        return view('videojuegos.view', ['videojuego' => Videojuego::find($id)]);
    }
}
