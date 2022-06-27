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

  /*   public $id;
    public $total_facturado;
    public $estado;
    public $fecha_de_creacion;

    public function __construct()
    {
    }

    public static function crearMesa($fecha_de_creacion)
    {
        $mesa = new Mesa();
        $mesa->total_facturado = 0;
        $mesa->estado = LIBRE;
        $mesa->fecha_de_creacion = $fecha_de_creacion;

        return $mesa;
    }

    public function guardarMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (total_facturado, estado,fecha_de_creacion) VALUES (:total_facturado, :estado, :fecha_de_creacion)");
        $consulta->bindValue(':total_facturado', $this->total_facturado, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_de_creacion', $this->fecha_de_creacion, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
}
 */