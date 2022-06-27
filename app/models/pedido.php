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

   /*  public function __construct()
    {
    }

    public static function crearPedido($codigo, $nombre_cliente, $descripcion, $idMesa, $sector, $cantidad, $precio_total, $fecha_de_creacion, $tiempo_estimado, $estado)
    {
        $pedido = new Pedido();
        $pedido->codigo = $codigo;
        $pedido->nombre_cliente = $nombre_cliente;
        $pedido->descripcion = $descripcion;
        $pedido->idMesa = $idMesa;
        $pedido->sector = $sector;
        $pedido->cantidad = $cantidad;
        $pedido->precio_total = $precio_total;
        $pedido->fecha_de_creacion = $fecha_de_creacion;
        $pedido->tiempo_estimado = $tiempo_estimado;
        $pedido->estado = $estado;

        return $pedido;
    }

    public function guardarPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigo, nombre_cliente ,descripcion, idMesa, sector, cantidad, precio_total, fecha_de_creacion, tiempo_estimado, estado)
         VALUES (:codigo, :nombre_cliente, :descripcion, :idMesa, :sector, :cantidad, :precio_total, :fecha_de_creacion, :tiempo_estimado, :estado)");
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':precio_total', $this->precio_total, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_de_creacion', $this->fecha_de_creacion, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo_estimado', $this->tiempo_estimado, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pedidos WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
}
 */