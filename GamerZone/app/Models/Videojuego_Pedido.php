<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videojuego_Pedido extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'videojuegos_pedidos';

    protected $fillable = ['id_videojuego', 'id_pedido', 'cantidad'];
}
