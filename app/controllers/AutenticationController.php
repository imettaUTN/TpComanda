<?php
require_once './interfaces/IApiUsable.php';
require_once './middlewares/AutentificadorJWT.php';

class AutenticationController
{
    function CrearToken($request, $response, $args) {    
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];
        $perfil = $parametros['perfil'];
    
        if($usuario == null)
        {
          $response->getBody()->write("Debe ingresar un usuario para generar el token");
          return $response;
        }
        if($perfil == null)
        {
          $response->getBody()->write("Debe ingresar un perfil para generar el token");
          return $response;
        }

        $datos = array('usuario' => $usuario, 'perfil' => $perfil);
    
        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token));
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    function TraerToken (Request $request, Response $response) {
       
        return $response->withHeader('Content-Type', 'application/json');
      }
    

}