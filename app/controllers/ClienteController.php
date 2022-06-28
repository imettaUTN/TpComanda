<?php
require_once './models/Mesa.php';
require_once './models/Empleado.php';
require_once './models/Cliente.php';
require_once './models/Usuario.php';
require_once './models/Pedido.php';

use \App\Models\Empleado as Empleado;
use \App\Models\Mesa as Mesa;
use \App\Models\Usuario as Usuario;
use \App\Models\Cliente as Cliente;

class ClienteController
{
  public function AtenderCliente($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $nombreCliente = $parametros['nombre'];    
    $idMesa = $parametros['idMesa'];  
    $idUsuario = $parametros['idUsuario'];  

    if($nombreCliente == null)
    {
      $response->getBody()->write("Nombre no valido o nulo");
      return $response;
    }

    $usuario = Usuario::where('id', $idUsuario)->first();

    if($usuario == null )
    {
      $response->getBody()->write("Usuario no valido o nulo");
      return $response;
    }

    if( $usuario->perfil == 'CLIENTE')
    {
      $response->getBody()->write("El usuario no es cliente");
      return $response;
    }
    $mesa = Mesa::where('id', $idMesa)->first();

    if($mesa == null)
    {
      $response->getBody()->write("Mesa no valida o nula");
      return $response;
    }

    // Creamos el cliente
    $cliente = new Cliente();
    $cliente->idMesa = $mesa->id;
    $cliente->nombre = strtoupper($nombreCliente);
    $cliente->idUsuario = $usuario->id;
    $cliente->save();

    //cambio el estado de la mesa para saber que va a estar ocupada por el cliente 
    $mesa ->estado = "con cliente esperando pedido";
    $mesa->save();

    $payload = json_encode(array("mensaje" => "Cliente creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  
  public function TraerTodos($request, $response, $args)
  {
    $lista = Cliente::all();
    $payload = json_encode(array("listaClientes" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function EstadoPedido($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $nroPedido = $parametros['nroPedido'];  
    $nroMesa = $parametros['nroMesa'];  
    $tiempoPedido = DB::select('select TIMESTAMPDIFF(MINUTE,fechaInicio,NOW()) where codigo = ? ', $nroPedido);

    $response->getBody()->write(array("Tiempo restante pedido " => $tiempoPedido));
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
