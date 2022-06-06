<?php
require_once './models/pedido.php';
require_once './interfaces/IApiUsable.php';
require_once './models/producto.php';
require_once './models/mesa.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $sector = null;
        $precio_total = null;
        $tiempo_estimado = null;
        $digitosCodigo = 5;
        $mensaje = "Error al cargar el pedido";

        $parametros = $request->getParsedBody();

        $codigo = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $digitosCodigo);
        $nombre_cliente = $parametros['nombre_cliente'];
        $descripcion = $parametros['descripcion'];
        $cantidad = $parametros['cantidad'];
        $idMesa = $parametros['idMesa'];
        $fecha_de_creacion = date("Y-m-d H:i:s");;

        $producto = Producto::obtenerProducto($descripcion);
        $mesa = Mesa::obtenerMesa($idMesa);

        if ($producto && $mesa) {
            $sector = $producto->sector;
            $precio_total = $producto->precio * $cantidad;
            $tiempo_estimado = $producto->tiempo_de_preparacion;

            $pedido = Pedido::crearPedido($codigo, $nombre_cliente, $descripcion, $idMesa, $sector, $cantidad, $precio_total, $fecha_de_creacion, $tiempo_estimado);
            $pedido->guardarPedido();
            $mensaje = "El pedido fue cargado";
        }

        $payload = json_encode(array("mensaje" => $mensaje));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $codigo = $args['codigo'];
        $pedido = Pedido::obtenerPedido($codigo);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("lista de pedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
    }

    public function BorrarUno($request, $response, $args)
    {
    }
}
