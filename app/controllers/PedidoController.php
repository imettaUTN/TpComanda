<?php
require_once './models/Pedido.php';
require_once './models/Empleado.php';
require_once './models/ProductosPedido.php';

use \App\Models\Pedido as Pedido;
use \App\Models\Empleado as Empleado;
use \App\Models\ProductosPedido as ProductosPedido;
use \App\Models\Producto as Producto;
use Carbon\Carbon;

class PedidoController implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {
    //genero el codigo random del pedido
    $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < 5; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }

     //primero asigno el empleado que va a tener el pedido
      $parametros = $request->getParsedBody();
      // a que sector pertenece los pedidos
      $sector = $parametros['sector'];
      $idProductos = $parametros['idProductos'];
      $idMesa = $parametros['idMesa'];
      //cliente de la comanda
      $idCliente = $parametros['idCliente'];

      if(strtoupper($sector) !== 'BARTENDER' && strtoupper($sector) !== 'CERVECERO' && strtoupper($sector) !== 'COCINERO' && strtoupper($sector) !== 'MOZOS')
      {
        $response->getBody()->write("sector invalido");
        return $response;
      }  
      //BUSCO EL PRIMER EMPLEADO DEL SECTOR LIBRE PARA TOMAR EL PEDIDO
      $empleadosLibresxSector = Empleado::where('estado', 'LIBRE')->where('sector',$sector)->first();
      if(is_null($empleadosLibresxSector))
      {
        $payload = json_encode(array("mensaje" => "No hay empleados libres para tomar el pedido"));
        $response->withStatus(204);
      }
      else
      { 
         // Creamos el pedido
        $pedido = new Pedido();
        $pedido->estado = 
        $pedido->idEmpleadoResponzable = $empleadosLibresxSector->id;
        $pedido->tiempoPedido = 0;
        $pedido->idMesa = $idMesa;
        $pedido->codigo = $random_string;
        $pedido->save();
        //inserto en tabla pedidoProductos
        $tiemposEstimadoPedido =[];
        
        foreach (explode(',',$idProductos) as $idProducto) 
        {
          $pedidoProductos = new ProductosPedido();
          $pedidoProductos->pedido_id= $pedido->id;
          $pedidoProductos->idProducto= $idProducto;
          $producto = Producto::where('id',$idProducto)->first();
          if($producto == null)
          {
            $payload = json_encode(array("mensaje" => "producto invalido". $idProducto));
            break;
          }
          else
          {
            array_push($tiemposEstimadoPedido,$producto->tiempoPedido);
            $pedidoProductos->save();
          }
        }
        $tiempoEst = max($tiemposEstimadoPedido);
        //actualizo el tiempo de pedido
        $pedido->tiempoPedido =$tiempoEst;
        $pedido->save();

        //actualizo el estado del empleado          
        $empleadosLibresxSector->estado = "OCUPADO";
        $empleadosLibresxSector->save();

        $payload = json_encode(array("mensaje" => "pedido creado con exito. Id pedido ". $pedido->id ." tiempo pedido estimado :" . $tiempoEst));
      }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $id = $args['id'];

    $pedido = Pedido::where('id', $id)->first();

    $payload = json_encode(array("Pedido"=>$pedido));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  //get
  public function TraerTodos($request, $response, $args)
  {
    $idUser = $args['idUsuario'];
    $emp = Empleado::where('id', $idUser);

    if(is_null($emp))
    {
      $response->getBody()->write("usuario invalido");
    }
    if($emp->perfil !== 'ADMIN')
    {
      $response->getBody()->write("usuario no autorizado");
      $response->withStatus(401);
    }


    $lista = Pedido::all();
    $payload = json_encode(array("listaPedidos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $estadoPedido = $parametros['estado'];
    $tiempoPedido = $args['tiempo'];
    $pedidoId = $args['id'];

    // Conseguimos el objeto
    $pedido = Pedido::where('id', '=', $pedidoId)->first();

    // Si existe
    if ($pedido !== null) {
      if($tiempoPedido !== null)
      {
        $pedido->tiempoPedido = $tiempoPedido;
      }
      if($estadoPedido !== null)
      {
        $pedido->estado = $estadoPedido;
      }
      // Guardamos en base de datos
      $pedido->save();
      $payload = json_encode(array("mensaje" => "pedido modificado con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "pedido no encontrado"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $response->withStatus(404);
     $payload = json_encode(array("mensaje" => "No se puede borrar un pedido"));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
