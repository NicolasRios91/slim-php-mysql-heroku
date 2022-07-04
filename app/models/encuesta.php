<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

require_once './utils/encuesta.php';
class Encuesta extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'encuestas';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'mesa_id', 'puntuacion_restaurante', 'puntuacion_mozo', 'puntuacion_producto', 'comentario',
        'fecha_de_creacion', 'puntuacion_total'
    ];
}
