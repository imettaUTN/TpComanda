<?php
use Slim\Psr7\Response;
class Logger
{
    public static function LogOperacion($request, $response, $next)
    {
        $retorno = $next($request, $response);
        return $retorno;
    }
    public static function VerificarCredenciales($request, $handler)
    {
        $requestType = $request->getMethod();
        $response = new response();
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            AutentificadorJWT::verificarToken($token);
            $esValido = true;
          } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
          }

        if($esValido)
        {
            if($requestType == "GET")
            {
                $response->getBody()->write('Metodo ' . $requestType .'no verifica' );

            }elseif ($requestType == "POST"){

               // $response->getBody()->write('Metodo ' . $requestType .' verifica' );
                $dataParseada= $request->getParsedBody();
                $sector = $dataParseada['sector'];
                if( $sector > 4 || $sector <1)
                {
                    $response->getBody()->write('Metodo ' . $requestType .' sector invalido' );
                    return $response;
                }
                $response->getBody()->write('Bienvenido ' . $nombre );
                $handler->handle($request);                        

            }
        }
        return $response;
    }
}
