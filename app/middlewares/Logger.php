<?php
use Slim\Psr7\Response;
require_once './models/Usuario.php';

class Logger 
{
    public static function LogOperacion($request, $handler)
    {
        $requestType = $request->getMethod();
        $response = $handler->handle($request);
        $response->getBody()->write("hola muchachos la peticion se hizo con:" . $requestType );
        return $response;
    }

    public static function VerificarCredenciales($request, $handler)
    {
        $requestType = $request->getMethod();
        $response = new response();
        $response->getBody()->write('Bienvenido '  );

        $response = $handler->handle($request);    
        return $response;
        if($requestType == "GET")
        {
            $response->getBody()->write('Bienvenido '  );

            $response = $handler->handle($request);                

        }elseif ($requestType == "POST"){

            $response->getBody()->write('Metodo ' . $requestType .' verifica' );
            $dataParseada= $request->getParsedBody();
            $nombre = $dataParseada['nombre'];
            $perfil = $dataParseada['perfil'];
            $usuario = Usuario::obtenerUsuario($nombre);
    
            if($perfil == "admin")
            {
                $response->getBody()->write('Bienvenido ' . $nombre );
                $response = $handler->handle($request);                
            }
            else{
                $response->getBody()->write('Usuario no autorizado ' . $nombre . ' perfil: ' . $perfil . ' id:' . $usuario->id);              
                $response->withStatus(302);
            }

        }
   
        //$response->getBody()->write("hola muchachos la peticion se hizo con:" . $requestType );
        return $response;
    }
}