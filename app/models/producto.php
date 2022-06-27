<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

require_once './utils/producto.php';
class Producto extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'productos';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'descripcion', 'sector', 'precio', 'tipo'
    ];
}
