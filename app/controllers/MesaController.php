<?php

require_once './models/mesa.php';
require_once './models/venta.php';
require_once './interfaces/IApiUsable.php';

use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Venta;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Date;

class MesaController implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $fecha_de_creacion = date("Y-m-d H:i:s");
        $mesa = new Mesa();
        $mesa->fecha_de_creacion = $fecha_de_creacion;
        $mesa->total_facturado = 0;
        $mesa->estado = LIBRE;
        $mesa->save();
        $mensaje = "La mesa fue creada";

        $payload = json_encode(array("mensaje" => $mensaje));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $mesa = Mesa::where('id', '=', $id)->first();
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::all();
        $payload = json_encode(array("lista de Mesas" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
    }

    public function BorrarUno($request, $response, $args)
    {
        try {
            $parametros = $request->getParsedBody()["body"];
            $id = $parametros["id"];
            Mesa::find($id)->delete();

            $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array("Ocurrio un error al borrar la Mesa" . $ex->getMessage() => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function CambiarEstado($request, $response, $args)
    {
        try {
            $parametros = $request->getParsedBody()["body"];
            $codigo_pedido = $parametros["codigo_pedido"];
            $nuevo_estado = $parametros["nuevo_estado"];
            $tipoUsuario = $request->getParsedBody()["token"]->tipoUsuario;

            $pedido = Pedido::where('codigo', '=', $codigo_pedido)->first();
            $mesa = Mesa::where('id', $pedido->idMesa)->first();

            if ($pedido->estado === 'cancelado') {
                throw new Exception("no se puede modificar un pedido ya cancelado");
            }

            if ($nuevo_estado === 'cerrada' && $tipoUsuario !== SOCIO) {
                throw new Exception("solo el socio puede cerrar la mesa");
            }

            $mesa->estado = $nuevo_estado;
            $mensaje = "Se cambio el estado de la mesa";
            if ($nuevo_estado === 'cerrada') {
                $venta = new Venta();
                $venta->id_mesa = $mesa->id;
                $venta->importe = $pedido->precio_total;
                $venta->fecha_de_creacion = date("Y-m-d H:i:s");
                $venta->save();
                $mesa->total_facturado = $mesa->total_facturado += $venta->importe;
                $mesa->save();
                $mensaje = "Se cambio el estado de la mesa y se creo la venta";
            }

            $payload = json_encode(array("mensaje" => $mensaje));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array("error" => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    // cantidad de veces que fue usada
    public function MasUsada($request, $response, $args)
    {
        try {
            $mesas = Capsule::table("ventas")
                ->select(Capsule::raw('COUNT(*) as cantidad_usos_total, id_mesa'))
                ->orderByDesc("cantidad_usos_total")
                ->groupBy("id_mesa")
                ->limit(1)
                ->get();

            $datos = json_encode($mesas);
            $response->getBody()->write($datos);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array("Error" => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    // cantidad de veces que fue usada
    public function MenosUsada($request, $response, $args)
    {
        try {
            $mesas = Capsule::table("ventas")
                ->select(Capsule::raw('COUNT(*) as cantidad_usos_total, id_mesa'))
                ->orderBy("cantidad_usos_total", "asc")
                ->groupBy("id_mesa")
                ->limit(1)
                ->get();

            $datos = json_encode($mesas);
            $response->getBody()->write($datos);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array("Error" => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    // facturas con mayor y menor importe
    public function MayorYMenorFactura($request, $response, $args)
    {
        try {
            $criterio = $args["criterio"];
            $datos = $request->getQueryParams();
            if ($criterio == "mayor") {
                $mesas = Capsule::table("ventas")
                    ->orderByDesc("importe")
                    ->groupBy("id_mesa")
                    ->limit(1)
                    ->get();
            } else if ($criterio == "menor") {
                $mesas = Capsule::table("ventas")
                    ->orderBy("importe", "asc")
                    ->groupBy("id_mesa")
                    ->limit(1)
                    ->get();
            }
            $datos = json_encode($mesas);
            $response->getBody()->write($datos);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array("Error" => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    // importe total por cada mesa
    public function FacturasPorMesa($request, $response, $args)
    {
        try {
            $idMesa = $args["id"];
            $mesas = Capsule::table("ventas")
                ->select(Capsule::raw('SUM(importe) as cantidad_vendida_total, id_mesa'))
                ->where("id_mesa", "=", $idMesa)
                ->get();

            $datos = json_encode($mesas);
            $response->getBody()->write($datos);
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array("Error" => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
