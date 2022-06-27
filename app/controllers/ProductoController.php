<?php
require_once './models/producto.php';
require_once './interfaces/IApiUsable.php';

use App\Models\Producto;

class ProductoController implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        try {
            $parsedBody = $request->getParsedBody();
            $parametros = $parsedBody["body"];
            $descripcion = $parametros['descripcion'];
            $sector = strtolower($parametros['sector']);
            $tipo = strtolower($parametros['tipo']);
            $precio = $parametros['precio'];

            $producto = new Producto();
            $producto->descripcion = $descripcion;
            $producto->sector = $sector;
            $producto->tipo = $tipo;
            $producto->precio = $precio;

            if (!(validarProducto($sector, $tipo))) {
                throw new Exception("sector o tipo invalido");
            }

            $producto->save();
            $payload = json_encode(array("mensaje" => "Producto creado con exito"));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array($ex->getMessage() => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    public function TraerUno($request, $response, $args)
    {
        $descripcion = $args['descripcion'];
        $producto = Producto::where('descripcion', "=", $descripcion)->first();
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::all();
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

        try {
            $parametros = $request->getParsedBody()["body"];
            $id = $parametros["id"];
            Producto::find($id)->delete();

            $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array("Ocurrio un error al borrar el Producto" . $ex->getMessage() => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
