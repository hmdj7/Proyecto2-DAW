<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use PDO;
use PDOException;

class BD extends Model
{
    // Conectar a la base de datos
    public function conectarBD(){

        try {
            $dsn = 'mysql:host=localhost;dbname=juegos';
            $user = 'root';
            $pass = '';
            $pdo = new PDO($dsn, $user, $pass);
            return $pdo;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    // Cerrar la base de datos
    public function cerrarBD(){
        $this->pdo = null;
    }
}
