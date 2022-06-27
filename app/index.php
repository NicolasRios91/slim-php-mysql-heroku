<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Handlers\Strategies\RequestHandler;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middlewares/Logger.php';
require_once './middlewares/autenticar.php';
require_once './middlewares/accesos.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();
//$app->setBasePath('/app');

// Add error middleware test de heroku
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

$container = $app->getContainer();
$capsule = new Capsule;
$capsule->addConnection([
  'driver'    => 'mysql',
  'host'      => $_ENV['MYSQL_HOST'],
  'database'  => $_ENV['MYSQL_DB'],
  'username'  => $_ENV['MYSQL_USER'],
  'password'  => $_ENV['MYSQL_PASS'],
  'charset'   => 'utf8',
  'collation' => 'utf8_unicode_ci',
  'prefix'    => '',
  'strict' => false,
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

// Routes

$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \UsuarioController::class . ':Login');
});

$app->group('/usuarios', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{nombre}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->delete('/', \UsuarioController::class . ':BorrarUno');
})->add(\MWAccesos::class . ':soloAdministradores')->add(\MWAutenticar::class . ':verificarUsuario');

$app->group('/productos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{descripcion}', \ProductoController::class . ':TraerUno');
  $group->post('[/]', \ProductoController::class . ':CargarUno');
  $group->delete('[/]', \ProductoController::class . ':BorrarUno');
})->add(\MWAccesos::class . ':soloAdministradores')->add(\MWAutenticar::class . ':verificarUsuario');

$app->group('/mesas', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{id}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno');
  $group->delete('[/]', \MesaController::class . ':BorrarUno');
})->add(\MWAutenticar::class . ':verificarUsuario');

$app->group('/pedidos', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{codigo}', \PedidoController::class . ':TraerUno');
  $group->post('[/]', \PedidoController::class . ':CargarUno');
  $group->put('[/]', \PedidoController::class . ':CambiarEstado');
  $group->delete('[/]', \PedidoController::class . ':BorrarUno');
})->add(\MWAccesos::class . ':TodosLosUsuarios')->add(\MWAutenticar::class . ':verificarUsuario');

$app->get('[/]', function (Request $request, Response $response) {
  $response->getBody()->write("Slim Framework 4 PHP test heroku");
  return $response;
});

$app->run();
