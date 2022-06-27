<?php
require_once './models/pedido.php';
require_once './interfaces/IApiUsable.php';
require_once './models/producto.php';
require_once './models/mesa.php';

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Mesa;
use App\Models\RegistroPedido;

class PedidoController implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $sector = null;
        $precio_total = null;
        $tiempo_estimado = null;
        $digitosCodigo = 5;
        $mensaje = "Error al cargar el pedido";

        $parametros = $request->getParsedBody()["body"];

        $codigo = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 1, $digitosCodigo);
        $nombre_cliente = $parametros['nombre_cliente'];
        $descripcion = $parametros['descripcion'];
        $cantidad = $parametros['cantidad'];
        $idMesa = $parametros['idMesa'];
        $fecha_de_creacion = date("Y-m-d H:i:s");;

        $producto = Producto::where('descripcion', '=', $descripcion)->first();
        $mesa = Mesa::where('id', '=', $idMesa)->first();

        if ($producto && $mesa) {
            $sector = $producto->sector;
            $precio_total = $producto->precio * $cantidad;

            $pedido = new Pedido();
            $pedido->codigo = $codigo;
            $pedido->nombre_cliente = $nombre_cliente;
            $pedido->descripcion = $descripcion;
            $pedido->cantidad = $cantidad;
            $pedido->idMesa = $idMesa;
            $pedido->sector = $sector;
            $pedido->precio_total = $precio_total;
            $pedido->tiempo_estimado = $tiempo_estimado;
            $pedido->fecha_de_creacion = $fecha_de_creacion;
            $pedido->save();
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
        $pedido = Pedido::where('codigo', '=', $codigo)->first();
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::all();
        $payload = json_encode(array("lista de pedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function CambiarEstado($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        try {
            $sectorUsuario = $request->getParsedBody()["token"]->sector;
            $tipoUsuario = $request->getParsedBody()["token"]->tipoUsuario;
            $idUsuario = $request->getParsedBody()["token"]->id;
            $codigo = $parametros["codigo"];
            $nuevoEstado = $parametros["estado"];
            $tiempo_estimado = $parametros["tiempo_Estimado"] || 1000;
            $pedido = Pedido::where("codigo", "=", $codigo)->first();
            $producto = Producto::where("descripcion", "=", $pedido->descripcion)->first();
            if ($producto->sector !== $sectorUsuario || $tipoUsuario === SOCIO) {
                throw new Exception("El empleado/socio no puede tomar este pedido");
            }

            $pedido->estado = $nuevoEstado;
            $pedido->tiempo_estimado = $tiempo_estimado;
            $pedido->fecha_de_modificacion = date("Y-m-d");
            if ($pedido->save()) {
                $datos = json_encode(array("Resultado" => "Pedido modificado con exito"));
                $response->getBody()->write($datos);
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(200);
            }
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array("Error" => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function BorrarUno($request, $response, $args)
    {
        try {
            $parametros = $request->getParsedBody()["body"];
            $id = $parametros["id"];
            Pedido::find($id)->delete();

            $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array("Ocurrio un error al borrar el Pedido" . $ex->getMessage() => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function ModificarUno($request, $response, $args)
    {
    }
}
