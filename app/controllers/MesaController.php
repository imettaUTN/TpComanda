<?php
require_once './models/Mesa.php';
require_once './models/Empleado.php';
require_once './models/Encuesta.php';
require_once './models/Usuario.php';

use \App\Models\Empleado as Empleado;
use \App\Models\Mesa as Mesa;
use \App\Models\Usuario as Usuario;
use \App\Models\Encuesta as Encuesta;

class MesaController implements IApiUsable
{
  //CREO LAS MESAS LIBRES, SIN CLIENTES 
  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    //genero el codigo random del pedido
    $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < 5; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    $numeroMesa = $parametros['numero'];    
    $estado = 'LIBRE';    

    if($numeroMesa == null)
    {
      $response->getBody()->write("Numero de mesa invalido o nulo");
      return $response;
    }
    if($estado == null)
    {
      $response->getBody()->write("Estado de mesa invalido o nulo");
      return $response;
    }

    //busco el primer mozo libre
    $mozo = Empleado::where('estado', 'LIBRE')->where('sector','MOZOS')->first();

    if($mozo == null)
    {
      $response->getBody()->write("No hay mozos libres para tomar la mesa");
      return $response;
    }

    // Creamos la mesa
    $mesa = new Mesa();
    $mesa->numero = $numeroMesa;
    $mesa->estado = strtoupper($estado);
    $mesa->idMozo = $mozo->id;
    $mesa->codigo=random_string;
    $mesa->save();

    //Le cambio el estado al mozo en 'OCUPADO' porque ya esta atendiendo una mesa 
    //Preguntar si un mozo puede antender varias mesas para ver si le cambio el estado o no

    $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $id = $args['id'];

    if($id == null)
    {
      $response->getBody()->write("id invalido o nulo");
      return $response;
    }

    $mesa = Mesa::where('id', $id)->first();

    $payload = json_encode($mesa);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Mesa::all();
    $payload = json_encode(array("listaMesas" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function CerrarMesa($request, $response, $args)
  {    
    $parametros = $request->getParsedBody();
    $idUser = $parametros['idUser'];  
    $puntMesa = $parametros['puntMesa'];  
    $txtMesa = $parametros['txtMesa'];      
    $puntResto = $parametros['puntResto'];  
    $txtResto = $parametros['txtResto'];  
    $puntMozo = $parametros['puntMozo'];  
    $txtMozo = $parametros['txtMozo'];  
    $puntCocinero = $parametros['puntCocinero'];  
    $txtCocinero = $parametros['txtCocinero'];  
    $idMesa = $parametros['txtCocinero'];  

    $usuario = Usuario::where('usuario', $idUser)->first();
    if(is_null($usuario) || $usuario->perfil !== 'ADMIN')
    {
      $response->getBody()->write("Usuario invalido o nulo");
      return $response;
    }
    
    $encuesta = new Encuesta();
    $encuesta->puntacionMesa = $puntMesa;
    $encuesta->textoMesa = strtoupper($txtMesa);
    $mesencuestaa->puntacionResto = $puntResto;
    $mesencuestaa->textoResto = strtoupper($txtResto);
    $mesencuestaa->puntacionMozo = $puntMozo;
    $mesencuestaa->textoMozo = strtoupper($txtMozo);
    $mesencuestaa->puntacionCocinero = $puntCocinero;
    $mesencuestaa->textoCocinero = strtoupper($txtCocinero);
    $mesencuestaa->mesa_id = $mozo->id;
    $response->getBody()->write("Mesa cerrada correctamente");
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function CambiarEstado($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $idMesa = $parametros['idMesa'];
    $estado = $parametros['estado'];

    if(is_null($estado) || $estado !=='con cliente esperando pedido' || $estado !=='con cliente comiendo'|| $estado !=='con cliente pagando')
    {
      $response->getBody()->write("Estado mesa no valido o nulo");
      return $response;
    }
    $mesa = Mesa::where('id', $idMesa)->first();
    $mesa->estado = $estado;      
    $mesa->save();
    $payload = json_encode(array("mensaje" => "mesa modificado con exito"));  
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];
    // Buscamos el producto
    $mesa = Mesa::find($id);
    // Borramos
    $mesa->delete();

    $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
