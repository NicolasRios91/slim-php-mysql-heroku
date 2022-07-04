<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $primaryKey = 'Id';
    protected $table = 'ventas';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'id_mesa', 'importe', 'fecha_de_creacion'
    ];
}
