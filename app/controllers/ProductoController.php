<?php
require_once './models/Producto.php';
use \App\Models\Producto as Producto;

class ProductoController implements IApiUsable
{

  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $desc = $parametros['descripcion'];    
    $tiempoPedido = $parametros['tiempoPedido'];    
    if($desc == null)
    {
      $response->getBody()->write("Descripcion de producto invalida o nula");
      return $response;
    }
    if($tiempoPedido == null)
    {
      $response->getBody()->write("Tiempo de producto invalido o nulo");
      return $response;
    }
    // Creamos el usuario
    $prod = new Producto();
    $prod->descripcion = strtoupper($desc);
    $prod->tiempoPedido = $tiempoPedido;
    $prod->save();

    $payload = json_encode(array("mensaje" => "Producto creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    // Buscamos usuario por nombre
    $id = $args['id'];
    $prod = Producto::where('id', $id)->first();

    $payload = json_encode($prod);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Producto::all();
    $payload = json_encode(array("listaProducto" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    if(isset($parametros['descripcion']))
    {
      $descripcion = $parametros['descripcion'];
    }

    if(isset($parametros['tiempoPedido']))
    {
      $tiempoPedido = $parametros['tiempoPedido'];
    }

    $id = $args['id'];

    if($id == null)
    {
      $response->getBody()->write("Id invalido o nulo");
      return $response;
    }

    // Conseguimos el objeto
    $prod = Producto::where('id', '=', $id)->first();

    // Si existe
    if ($prod !== null) {
      
      if(isset($descripcion))
      {     
         $prod->descripcion = strtoupper($descripcion);
      }
      if(isset($tiempoPedido))
      {
         $prod->tiempoPedido = $tiempoPedido;
      }

      // Guardamos en base de datos
      $prod->save();
      $payload = json_encode(array("mensaje" => "producto modificado con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "producto no encontrado"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];

    if($id == null)
    {
      $response->getBody()->write("Id de producto invalido o nulo");
      return $response;
    }

    // Buscamos el producto
    $prod = Producto::find($id);
    if($prod == null)
    {
      $response->getBody()->write("Producto invalido o nulo");
      return $response;
    }

    // Borramos
    $prod->delete();

    $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
