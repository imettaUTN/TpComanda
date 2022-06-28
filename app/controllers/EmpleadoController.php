<?php
require_once './models/Empleado.php';
require_once './models/Usuario.php';
require_once './models/Pedido.php';
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

use Carbon\Carbon;
use \App\Models\Usuario as Usuario;
use \App\Models\Empleado as Empleado;
use \App\Models\Pedido as Pedido;
use \App\Models\Mesa as Mesa;

class EmpleadoController implements IApiUsable
{

  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
//   'idUsuario','estado','sector','fechaBaja'
    $idUsuario = $parametros['idUsuario'];
    //AL DAR DE ALTA UN EMPLEADO, SE LO PONE COMO LIBRE
    $estado = 'LIBRE';
    $sector = $parametros['sector'];

    if($idUsuario == null)
    {
      $response->getBody()->write("Debe ingresar un usuario para obtener el usuario");
      return $response;
    }
    if($estado == null)
    {
      $response->getBody()->write("Debe ingresar un usuario para obtener el usuario");
      return $response;
    }


    if(strtoupper($sector) !== 'BARTENDER' && strtoupper($sector) !== 'CERVECERO' && strtoupper($sector) !== 'COCINERO' && strtoupper($sector) !== 'MOZOS')
    {
      $response->getBody()->write("sector invalido");
      return $response;
    }

    $usuario = Usuario::where('id', $idUsuario)->first();

    if($usuario == null)
    {
      $response->getBody()->write("Id de usuario invalido");
      return $response;
    }


    // Creamos el empleado
    $emp = new Empleado();
    $emp->idUsuario = $idUsuario;
    $emp->estado = strtoupper($estado);
    $emp->sector = strtoupper($sector);
    $emp->save();

    $payload = json_encode(array("mensaje" => "Empleado creado con exito"));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TomarPedidosPendientes($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    // a que sector pertenece los pedidos
    $idPedido = $parametros['idPedido'];
    $pedido = Pedido::where('id', $idPedido)->first();

    if(is_null($pedido))
    {
      $response->getBody()->write("Id pedido invalido o nulo");
      return $response;
    }
    $pedido ->estado = "en preparación";
    $pedido ->fechaInicio = Carbon::now();
    $pedido ->save();

    $response->getBody()->write("Pedido tomado correctamente");
    return $response
      ->withHeader('Content-Type', 'application/json');

  }

  public function EntregarPedido($request, $response, $args)
  {
    $idPedido = $args['id'];
    $pedido = Pedido::where('id', $idPedido)->first();

    if(is_null($pedido))
    {
      $response->getBody()->write("Id pedido invalido o nulo");
      return $response;
    }
    $pedido ->estado = "listo para servir";
    $pedido ->fechaFin = Carbon::now();
    $pedido ->save();

    $mesa = Mesa::where("id", $pedido->idMesa)->first();
    $mesa->estado = 'con cliente comiendo';

    $emp = Empleado::where("id",$pedido->idEmpleadoResponzable)->first();
    $emp->estado = "LIBRE";
    $emp->save();

    $response->getBody()->write("Pedido entregado correctamente");
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  
  public function PedidosPendientes($request, $response, $args)
  {
    $empl = $args['empleado'];
    $unEmpl = Empleado::where('id', $empl)->first();
    if(is_null($unEmpl) )
    {
      $response->getBody()->write("Id empleado invalido o nulo");
      return $response;
    }
    //de todos los pedidos, traigo los que pertenecen al empleado buscado
    $pedidos = Pedido::all()->where('idEmpleadoResponzable', $unEmpl->idUsuario);
    $pedidosArray = [];

    foreach ($pedidos as $pedido)
    {
      array_push($pedidosArray,$pedido->productos);
    }

    $payload = json_encode(array("listaPedidos" => $pedidosArray));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');

  }
  
  public function TraerUno($request, $response, $args)
  {
    // Buscamos usuario por nombre
    $empl = $args['empleado'];
    if($empl == null)
    {
      $response->getBody()->write("Id de empleado invalido");
      return $response;
    }

    $empleado = Empleado::where('id', $empl)->first();
    $payload = json_encode($empleado);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Empleado::all();
    $payload = json_encode(array("listaEmpleados" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $id = $parametros['id'];
    $estado = $parametros['estado'];
    $sector = $parametros['sector'];
    $idUser = $parametros['idUsuario'];

    if($id == null)
    {
      $response->getBody()->write("Id de empleado invalido");
      return $response;
    }
    if($idUser == null)
    {
      $response->getBody()->write("Id de usuario invalido");
      return $response;
    }
    
    //  'idUsuario','estado','sector','fechaBaja'
    // Conseguimos el objeto
    $emp = Empleado::where('id', '=', $id)->first();

    // Si existe
    if ($emp !== null) {
      // Seteamos un nuevo empleado


    //el milddware ya valido el usuario
    $usuario = Usuario::where('usuario', $idUser)->first();
    // Si existe
      if($estado !== null)
      {
        if( $usuario->perfil !== 'ADMIN')
        {
          $payload = json_encode(array("mensaje" => "usuario no autorizado para el cambio de estado"));
          $response->withStatus(401);
        }
        else
        {
          $emp->estado = strtoupper($estado);
        }
        if($estado == 'BAJA')
        {
          $emp->fechaBaja = Carbon::now();
        }
      }

      if($sector !== null)
      {
        $emp->sector =  strtoupper($sector);
      }      
      // Guardamos en base de datos
      $emp->save();
      $payload = json_encode(array("mensaje" => "Empleado modificado con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "Empleado no encontrado"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $empleadoId = $args['id'];
    // Buscamos el empleado
    $empleado = Empleado::find($empleadoId);
    // Borramos
    $empleado->delete();

    $payload = json_encode(array("mensaje" => "Empleado borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
