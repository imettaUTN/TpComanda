<?php
require_once './models/Comanda.php';
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{ 
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        //obtenego comanda
       $comanda = Comanda::obtenerComanda($parametros['codigoComanda']);     
        //Creo la comanda de la mesa
        $descripcion = $parametros['descripcionPedido'];
        // Creamos Comanda
        $comanda = new Pedido();
        $comanda->idMesa =  $mesa->idMesa;
        $comanda->nombreCliente = $nombreCliente;        
        $comanda->crearComanda();
        $payload = json_encode(array("mensaje" => "comanda creada con exito"));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    /// Al pedido le agrego el usuario que se encarga del pedido
    public function AsignarUsuarioPedido($request, $response, $args)
    {
        $codigoComanda =$parametros['codigoComanda'];
        $descPedido = $parametros['producto'];
        $usuarioName = $parametros['usuario'];
        $pedido = Pedido::obtenerPedido($codigoComanda,$descPedido);
        $user = Usuario::obtenerUsuario($usuarioName);
        $p = new Pedido();
        $p->AsignarPedido($pedido->id , $user-> id );
        $payload = json_encode(array("mensaje" => "Pedido asignado con exito"));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($request, $response, $args)
    {
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function BorrarUno($request, $response, $args)
    {
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function ModificarUno($request, $response, $args)
    {
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}
