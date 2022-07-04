<?php

require_once './models/encuesta.php';
require_once './interfaces/IApiUsable.php';

use App\Models\Encuesta;
use App\Models\Mesa;

class EncuestaController implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        try {
            $encuesta = new Encuesta();
            $encuesta->mesa_id = $parametros["mesa_id"];
            $encuesta->puntuacion_restaurante  = $parametros["puntuacion_restaurante"];
            $encuesta->puntuacion_mozo = $parametros["puntuacion_mozo"];
            $encuesta->puntuacion_producto = $parametros["puntuacion_producto"];
            $encuesta->comentario = $parametros["comentario"];
            $encuesta->fecha_de_creacion = date("Y-m-d H:i:s");
            $encuesta->puntuacion_total = $encuesta->puntuacion_restaurante + $encuesta->puntuacion_mozo + $encuesta->puntuacion_producto;

            if (
                !validarPuntuacion($encuesta->puntuacion_restaurante) ||
                !validarPuntuacion($encuesta->puntuacion_mozo) ||
                !validarPuntuacion($encuesta->puntuacion_producto)
            ) {
                throw new Exception("La puntuacion debe ser mayor a 0 y hasta 10 inclusive");
            }

            if (!Mesa::find($encuesta->mesa_id)) {
                throw new Exception("La mesa no existe");
            }

            if ($encuesta->save()) {
                $datos = json_encode(array("Resultado" => "Se cargo la encuesta"));
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

    public function TraerUno($request, $response, $args)
    {
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Encuesta::all();
        $payload = json_encode(array("lista de encuestas" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerMejoresEncuestas($request, $response, $args)
    {
        $lista = Encuesta::where('puntuacion_total', '>', 17)->get();
        $payload = json_encode(array("lista de mejores encuestas" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPeoresEncuestas($request, $response, $args)
    {
        $lista = Encuesta::where('puntuacion_total', '<', 18)->get();
        $payload = json_encode(array("lista de peores encuestas" => $lista));

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

    public function CambiarEstado($request, $response, $args)
    {
    }
}
