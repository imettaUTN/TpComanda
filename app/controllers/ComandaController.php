<?php
require_once './models/Comanda.php';
require_once './models/Mesa.php';
require_once './MesaController.php';

require_once './interfaces/IApiUsable.php';

class ComandaControlle extends Comanda implements IApiUsable
{ 
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        //obtenego mesa
       $mesa = Mesa::obtenerMesa($parametros['numeroMesa']);     
        //Creo la comanda de la mesa
        $nombreCliente = $parametros['nombreCliente'];
        // Creamos Comanda
        $comanda = new Comanda();
        $comanda->idMesa =  $mesa->idMesa;
        $comanda->nombreCliente = $nombreCliente;

        $comanda->crearComanda();
        $payload = json_encode(array("mensaje" => "comanda creada con exito"));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        // $usr = $args['usuario'];
        // $usuario = Usuario::obtenerUsuario($usr);
        // $payload = json_encode($usuario);

        // $response->getBody()->write($payload);
        // return $response
        //   ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodos($request, $response, $args)
    {
    }
    public function BorrarUno($request, $response, $args)
    {
    }
    public function ModificarUno($request, $response, $args)
    {
    }
    
}
