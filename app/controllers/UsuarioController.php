<?php
require_once './models/Usuario.php';
require_once './middlewares/jwt.php';
require_once './interfaces/IApiUsable.php';

use App\Models\Usuario;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;

class UsuarioController implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    $parsedBody = $request->getParsedBody();
    $parametros = $parsedBody["body"];
    $nombre = $parametros['nombre'];
    $apellido = $parametros['apellido'];
    $clave = $parametros['clave'];
    $tipo = strtolower($parametros['tipo']);
    $sector = strtolower($parametros['sector']);
    $fecha_de_ingreso = date("Y-m-d H:i:s");

    $mensaje = "El usuario no fue creado";
    $usr = new Usuario();
    $usr->nombre = $nombre;
    $usr->apellido = $apellido;
    $usr->clave = $clave;
    $usr->tipo = $tipo;
    $usr->sector = $sector;
    $usr->fecha_de_ingreso = $fecha_de_ingreso;
    $usr->estado = ACTIVO;

    //$usr = Usuario::crearUsuario($nombre, $apellido, $clave, $tipo, $sector, $fecha_de_ingreso);
    if ($usr !== null) {
      $usr->save();
      $mensaje = "Usuario creado con exito";
    }
    $payload = json_encode(array("mensaje" => $mensaje));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $nombre = $args['nombre'];
    $usuario = Usuario::where('nombre', "=", $nombre)->first();
    $payload = json_encode($usuario);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Usuario::all();
    $payload = json_encode(array("listaUsuario" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  // FALTA
  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombre = $parametros['nombre'];
    Usuario::modificarUsuario($nombre);

    $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    try {
      $parametros = $request->getParsedBody()["body"];
      $id = $parametros["id"];
      Usuario::find($id)->delete();

      $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    } catch (Exception $ex) {
      $error = $ex->getMessage();
      $datosError = json_encode(array("Ocurrio un error al borrar el usuario" . $ex->getMessage() => $error));
      $response->getBody()->write($datosError);
      return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
  }

  public function Login(Request $request, Response $response)
  {
    $datosIngresados = $request->getParsedBody();
    if (!isset($datosIngresados["clave"]) && !isset($datosIngresados["nombre"])) {
      $error = json_encode(array("Error" => "Datos incompletos"));
      $response->getBody()->write($error);
      return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(404);
    }
    try {
      $clave = $datosIngresados["clave"];
      $nombre = $datosIngresados["nombre"];
      $usuario = Usuario::where('nombre', "=", $nombre)->first();
      if ($usuario) {
        if (($clave === $usuario->clave)) {
          $datos = [
            "id" => $usuario->id,
            "nombre" => $usuario->nombre,
            "tipoUsuario" => $usuario->tipo,
            "estadoUsuario" => $usuario->estado,
            "sector" => $usuario->sector,
          ];
          $token = AutentificadorJWT::CrearToken($datos);
          $payload = json_encode(array("token" => $token));
          $response->getBody()->write($payload);
          return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
        } else {
          throw new Exception("La contraseña es incorrecta");
        }
      } else {
        throw new Exception("El usuario no existe");
      }
    } catch (Exception $ex) {
      $error = $ex->getMessage();
      $datosError = json_encode(array("Ocurrio un error al loguearse " . $ex->getMessage() => $error));
      $response->getBody()->write($datosError);
      return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
  }
}
