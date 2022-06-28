<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Illuminate\Database\Capsule\Manager as Capsule;


require __DIR__ . '/../vendor/autoload.php';
//require_once './middlewares/Logger.php';

require_once './middlewares/Login.php';
require_once './controllers/AutenticationController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ClienteController.php';


// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// Eloquent
$container=$app->getContainer();

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
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();


// Routes
//Usuario
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
    $group->put('/{id}', \UsuarioController::class . ':ModificarUno');
    $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
  })
  //->add(\Login::class . ":LogOperacion")
  ->add(\Login::class . ":VerificarCredenciales");

//Empleado
$app->group('/PedidosEmpleado', function (RouteCollectorProxy $group) {
  $group->get('/{empleado}', \EmpleadoController::class . ':PedidosPendientes');
  $group->put('/{id}', \EmpleadoController::class . ':EntregarPedido');
  $group->post('[/]', \EmpleadoController::class . ':TomarPedidosPendientes');  
})
->add(\Login::class . ":VerificarCredenciales");
//->add(\Login::class . ":VerificarCredenciales");


//Empleado
$app->group('/Empleado', function (RouteCollectorProxy $group) {
    $group->get('[/]', \EmpleadoController::class . ':TraerTodos');
    $group->get('/{id}', \EmpleadoController::class . ':TraerUno');
    $group->post('[/]', \EmpleadoController::class . ':CargarUno');
    $group->put('/{id}', \EmpleadoController::class . ':ModificarUno');
    $group->delete('/{id}', \EmpleadoController::class . ':BorrarUno');
  })
  ->add(\Login::class . ":VerificarCredenciales");
  //->add(\Login::class . ":VerificarCredenciales");

  
//Producto
$app->group('/Producto', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->get('/{id}', \ProductoController::class . ':TraerUno');
    $group->post('[/]', \ProductoController::class . ':CargarUno');
    $group->put('/{id}', \ProductoController::class . ':ModificarUno');
    $group->delete('/{id}', \ProductoController::class . ':BorrarUno');
  })
 // ->add(\Login::class . ":LogOperacion")
  ->add(\Login::class . ":VerificarCredenciales");


//Producto
$app->group('/Pedido', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{id}', \PedidoController::class . ':TraerUno');
  $group->post('[/]', \PedidoController::class . ':CargarUno');
  $group->put('/{id}', \PedidoController::class . ':ModificarUno');
  $group->delete('/{id}', \PedidoController::class . ':BorrarUno');
})
// ->add(\Login::class . ":LogOperacion")
->add(\Login::class . ":VerificarCredenciales");


//Cliente
$app->group('/Cliente', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ClienteController::class . ':TraerTodos');
 // $group->get('/{id}', \ClienteController::class . ':TraerUno');
  $group->post('[/]', \ClienteController::class . ':AtenderCliente');
 // $group->put('/{id}', \ClienteController::class . ':ModificarUno');
})
// ->add(\Login::class . ":LogOperacion")
->add(\Login::class . ":VerificarCredenciales");

//Mesa
$app->group('/Mesa', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos');
    $group->get('/{id}', \MesaController::class . ':TraerUno');
    $group->post('[/]', \MesaController::class . ':CargarUno');
    $group->put('/{id}', \MesaController::class . ':ModificarUno');
    $group->delete('/{id}', \MesaController::class . ':BorrarUno');
  })
 // ->add(\Login::class . ":LogOperacion")
  ->add(\Login::class . ":VerificarCredenciales");

  $app->post('/CerrarMesa', function (RouteCollectorProxy $group) {
    $group->post('[/]', \MesaController::class . ':CerrarMesa');
  })
  ->add(\Login::class . ":VerificarCredenciales");

  $app->post('/CambiarEstadoMesa', function (RouteCollectorProxy $group) {
    $group->post('[/]', \MesaController::class . ':CambiarEstado');
  })
  ->add(\Login::class . ":VerificarCredenciales");

  // JWT
$app->group('/jwt', function (RouteCollectorProxy $group) {
    $group->post('[/]', \AutenticationController::class . ':CrearToken');  
});

$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("TP IVAN METTA");
    return $response;

});

$app->run();
