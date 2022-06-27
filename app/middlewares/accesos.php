<?php

require_once './utils/usuario.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class MWAccesos
{
    public function SoloAdministradores(Request $request, RequestHandler $handler)
    {
        try {
            $data = $request->getParsedBody()["token"];
            if ($data->tipoUsuario === SOCIO) {
                $response = $handler->handle($request);
                return $response;
            } else {
                throw new Exception("No tiene permisos para esta operacion ");
            }
        } catch (Exception $ex) {
            throw new Exception("Error al verificar los datos del usuario " . $ex->getMessage(), 0, $ex);;
        }
    }

    public function AdministradoresYMozos(Request $request, RequestHandler $handler)
    {
        try {
            $data = $request->getParsedBody()["token"];
            if ($data->tipoUsuario === SOCIO || $data->sector === SALON) {
                $response = $handler->handle($request);
                return $response;
            } else {
                throw new Exception("No tiene permisos para esta operacion ");
            }
        } catch (Exception $ex) {
            throw new Exception("Error al verificar los datos del usuario " . $ex->getMessage(), 0, $ex);;
        }
    }

    public function TodosLosUsuarios(Request $request, RequestHandler $handler)
    {
        try {
            $data = $request->getParsedBody()["token"];
            if (validarUsuario($data->tipoUsuario)) {
                $response = $handler->handle($request);
                return $response;
            } else {
                throw new Exception("El tipo de usuario no es valido ");
            }
        } catch (Exception $ex) {
            throw new Exception("Error al verificar los datos del usuario " . $ex->getMessage(), 0, $ex);;
        }
    }
}
