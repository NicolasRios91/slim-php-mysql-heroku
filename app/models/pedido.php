<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'pedidos';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'codigo', 'nombre_cliente', 'descripcion', 'idMesa', 'sector',
        'cantidad', 'precio_total', 'fecha_de_creacion', 'fecha_de_modificacion', 'tiempo_estimado', 'estado'
    ];
}
