<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videojuego_Carrito extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'videojuegos_carritos';

    protected $fillable = ['id_videojuego', 'id_carrito', 'cantidad'];
}
