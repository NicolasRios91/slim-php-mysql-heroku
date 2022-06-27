<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

require_once './utils/mesa.php';
class Mesa extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'mesas';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'total_facturado', 'estado', 'fecha_de_creacion'
    ];
}
