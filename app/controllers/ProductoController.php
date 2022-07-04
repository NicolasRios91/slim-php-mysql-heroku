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
        $parametros = $request->getParsedBody()["body"];
        $payload = json_encode(array("mensaje" => "No se modifico el producto"));

        $descripcion = $parametros["descripcion"];
        $producto = Producto::where('descripcion', '=', $descripcion)->first();
        //var_dump($usuario);
        if ($producto) {
            $producto->descripcion = $descripcion;
            $producto->save();
            $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
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

    // archivos
    public function DescargarCSV($request, $response, $args)
    {
        $ruta = __DIR__  . "productos.csv";
        try {
            $archivo = fopen($ruta, "a");
            if ($archivo != null) {
                $lista = Producto::all();
                foreach ($lista as $producto) {
                    fwrite($archivo, $this->ConvertirACvs($producto));
                }
                fclose($archivo);
            }
            if (readfile($ruta)) {
                unlink($ruta);
                return $response
                    ->withHeader('Content-Type', 'application/csv')
                    ->withStatus(200);
            }
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array("Error" => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function CargaCSV($request, $response, $args)
    {
        try {
            if ($request->getUploadedFiles()) {
                $archivo = $request->getUploadedFiles();
                $destino = __DIR__ . "productos.csv";
                $nombreAnterior = $archivo['archivo']->getClientFileName();
                $extension = explode(".", $nombreAnterior);
                $extension = array_reverse($extension)[0];
                if ($extension != "csv") {
                    throw new Exception("El tipo de archivo es incorrecto");
                }
                $pathArchivo = $destino;
                $archivo = $archivo['archivo'];
                $archivo->moveTo($pathArchivo);

                $archivoAbierto = fopen($pathArchivo, 'r');
                $arrayObjetos = array();
                if ($archivoAbierto != null) {
                    while (!feof($archivoAbierto)) {
                        $aux = fgets($archivoAbierto);
                        $fila = explode(',', $aux);
                        if (
                            isset($fila[0]) && !empty($fila[0]) &&
                            isset($fila[1]) && !empty($fila[1]) &&
                            isset($fila[2]) && !empty($fila[2]) &&
                            isset($fila[3]) && !empty($fila[3])
                        ) {
                            $prod = (object)[
                                "descripcion" => $fila[0], "sector" => $fila[1],
                                "precio" => $fila[2], "tipo" => $fila[3]
                            ];
                            array_push($arrayObjetos, $prod);
                        }
                    }
                    fclose($archivoAbierto);
                }
                return $response
                    ->withHeader('Content-Type', 'application/csv')
                    ->withStatus(200);
            }
            throw new Exception("No se subio ningun archivo");
        } catch (Exception $ex) {
            $error = $ex->getMessage();
            $datosError = json_encode(array("error" => $error));
            $response->getBody()->write($datosError);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function ConvertirACvs($producto)
    {
        $datos = "$producto->descripcion,$producto->sector,$producto->precio,$producto->tipo,\n";
        return $datos;
    }
}
