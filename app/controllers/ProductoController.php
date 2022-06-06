<?php
require_once './models/producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];
        $sector = strtolower($parametros['sector']);
        $tipo = strtolower($parametros['tipo']);
        $precio = $parametros['precio'];
        //todo hacerlo random en el pedido
        $tiempo_de_preparacion = $parametros['tiempo_de_preparacion'];

        $mensaje = "El producto no fue creado";

        $producto = Producto::crearProducto($descripcion, $sector, $tipo, $precio, $tiempo_de_preparacion);
        if ($producto !== null) {
            $producto->guardarProducto();
            $mensaje = "Producto creado con exito";
        }
        $payload = json_encode(array("mensaje" => $mensaje));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $descripcion = $args['descripcion'];
        $producto = Producto::obtenerProducto($descripcion);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("lista de Productos" => $lista));

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
