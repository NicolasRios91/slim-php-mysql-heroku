<?php

require_once './models/mesa.php';
require_once './interfaces/IApiUsable.php';

use App\Models\Mesa;

class MesaController implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $fecha_de_creacion = date("Y-m-d H:i:s");;
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
}
