<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'usuarios';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'nombre', 'apellido', 'clave', 'tipo',
        'sector', 'estado', 'fecha_de_ingreso',
    ];
}
