<?php
require_once './utils/producto.php';
class Producto
{
    public $id;
    public $descripcion;
    public $sector;
    public $precio;
    public $tiempo_de_preparacion;
    public $tipo;

    public function __construct()
    {
    }

    public static function crearProducto($descripcion, $sector, $tipo, $precio, $tiempo_de_preparacion,)
    {
        $producto = new Producto();
        $producto->descripcion = $descripcion;
        $producto->precio = $precio;
        if (validarProducto($sector, $tipo)) {
            $producto->sector = $sector;
            $producto->tipo = $tipo;
        } else {
            return null;
        }

        $producto->tiempo_de_preparacion = $tiempo_de_preparacion;

        return $producto;
    }

    public function guardarProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (descripcion, sector,tipo, precio, tiempo_de_preparacion) VALUES (:descripcion, :sector, :tipo, :precio, :tiempo_de_preparacion)");
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':tiempo_de_preparacion', $this->tiempo_de_preparacion, PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($descripcion)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productos WHERE descripcion = :descripcion");
        $consulta->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }
}
