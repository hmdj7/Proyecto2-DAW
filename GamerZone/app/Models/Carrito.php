<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    
    use HasFactory;
    public $timestamps = false;

    protected $table = 'carritos';

    protected $fillable = [
        'id_usuario'
    ];
}