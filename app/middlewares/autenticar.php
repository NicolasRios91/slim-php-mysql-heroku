
<?php
require_once './middlewares/jwt.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Firebase\JWT\JWT;

class MWAutenticar
{
    public function VerificarUsuario(Request $request, RequestHandler $handler)
    {
        try {
            if (empty($request->getHeaderLine('Authorization'))) {
                throw new Exception("Falta token de autorización");
            }
            $header = $request->getHeaderLine('Authorization');
            $contenidoRequest = $request->getParsedBody();
            self::VerificarToken($header);
            $payload = array("body" => $contenidoRequest, "token" => self::ObtenerDataToken($header));
            $request = $request->withParsedBody($payload);
            $response = $handler->handle($request);
            return  $response;
        } catch (Exception $ex) {
            throw new Exception("Ocurrio un problema " . $ex->getMessage(), 0, $ex);
        }
    }
    private static function VerificarToken($token)
    {
        if (empty($token)) {
            throw new Exception("El token se encuentra vacio");
        }
        try {
            $tokenDecodificado = JWT::decode($token, $_ENV['CLAVE_SECRETA'], [$_ENV['ENCRIPTACION']]);
            if ($tokenDecodificado->aud !== AutentificadorJWT::Aud()) {
                throw new Exception("No es un usuario válido ");
            }
            return $tokenDecodificado;
        } catch (Exception $ex) {
            throw new Exception("Verificar token " . $ex->getMessage(), 0, $ex);
        }
    }
    public static function ObtenerDataToken($token)
    {
        if (empty($token)) {
            throw new Exception("El token se encuentra vacio");
        }
        try {
            $tokenDecodificado = JWT::decode($token, $_ENV['CLAVE_SECRETA'], [$_ENV['ENCRIPTACION']]);
            return $tokenDecodificado->data;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
