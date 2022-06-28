<?php
use Slim\Psr7\Response;
use Carbon\Carbon;

//require_once './models/Usuario.php';

class Login 
{
    public static function LogOperacion2($request, $handler)
    {
        $requestType = $request->getMethod();
      //  $response = $handler->handle($request);
      $response = new response();
      $uri = ($request->getUri())->getPath();
      //$path = $request->getBasePath();
        $response->getBody()->write("hola muchachos la peticion se hizo con:".dirname(__DIR__)  );
        return $response;
    }
    //        'fecha', 'tipo', 'descripcion', 'idUsuario'
    public static function LogOperacion($request, $handler)
    {
        $requestType = $request->getMethod();
        $dataParseada= $request->getParsedBody();
        $basePath = ($request->getUri())->getPath();

        $response = new response();       
        $usr = $request->getHeaderLine('usuario');
        if($usr == null)
        {
            $response->getBody()->write('Falta id de usuario');              
            $response->withStatus(302);
        }
        else
        {

            $descripcion = "Ingreso al sistema";
            $tipo = "Ingreso";
         

            // si es un post nunca voy a tener el usuario creado porque lo estoy creando
            if($requestType == "POST" && str_contains($basePath,"Empleado"))
            {
                $usuario =  new Usuario();
                $usuario->id = 0;
                $tipo = "Nuevo Usuario";
                $descripcion ="Creo Usuario";
            }
            else
            {
                 $usuario = Usuario::where('usuario', $usr)->first();
            }
            if($usuario == null)
            {
               $response->getBody()->write('Usuario no encontrado');              
               $response->withStatus(302);
            }
            else
            {                
                $log = new Logs();
                $log->fecha = Carbon::now();
                $log->tipo = $tipo;
                $log->descripcion = $descripcion;
                $log->idUsuario = $usuario->id;
                $log.save();
                $response = $handler->handle($request);
            }
        }
        return $response;
    }
    
    public static function VerificarCredenciales($request, $handler)
    {
        $requestType = $request->getMethod();
        $response = new response();
        $header = $request->getHeaderLine('Authorization');
        if($handler == null)
        {
            $response->getBody()->write('Debe ingresar token de autenticacion'); 
        }
        $token = trim(explode("Bearer", $header)[1]);
        //obtengo el path de la llamada para saber si es necesario ser admin para acceder al metodo
        $basePath =  ($request->getUri())->getPath();
     

        try {
            AutentificadorJWT::verificarToken($token);
            $esValido = true;
          } catch (Exception $e) {
            $payload = json_encode(array('error' => $e->getMessage()));
            $response->getBody()->write($payload);
          }

        if($esValido)
        {
            // $dataParseada= $request->getParsedBody();
            // $usr = $dataParseada['usuario'];
            // $pasw = $dataParseada['pasw'];

            // if($usr == null)
            // {
            //     $response->getBody()->write('Falta usuario');              
            //     $response->withStatus(302);
            //     return $response;
 
            // }
            // if($pasw == null)
            // {
            //     $response->getBody()->write('Falta usuario');              
            //     $response->withStatus(302);
            //     return $response;

            // }
            //  $usuario = Usuario::where('usuario', $usr)->first();
            //  if($usuario != null)
            //  {
            //     if($usuario->clave == $pasw) 
            //     {
            //         $response->getBody()->write('Credenciales invalidas');              
            //         $response->withStatus(302);
            //         return $response;
            //     }
                
            //     if( $requestType == 'PUT' && $usuario !== 'ADMIN' )
            //     {
            //         if(str_contains($basePath,'mesa') && $usuario !== 'EMPLEADO' )
            //         {
            //             $emp = Empleado::where('userID', $usuario->userID)->first();
            //             if($emp->sector !="MOZOS")
            //             {
            //                 $response->getBody()->write('Perfil no autorizado');              
            //                 $response->withStatus(401);
            //                 return $response;

            //             }
            //         }
            //         else
            //         {
            //         $response->getBody()->write('Credenciales invalidas');              
            //         $response->withStatus(302);
            //         }
            //     }

            //     //Tengo que definir bien el path de los reportes pero la idea seria validarlo asi el perfil
            //     if($usuario->perfil !== 'CLIENTE' && str_contains($basePath,'consulta'))
            //     {
            //         $response->getBody()->write('Perfil no autorizado');              
            //         $response->withStatus(401);
            //         return $response;
            //     }               
            //     //Tengo que definir bien el path de los reportes pero la idea seria validarlo asi el perfil
            //     if($usuario->perfil !== 'ADMIN' && str_contains($basePath,'reporte'))
            //     {
            //         $response->getBody()->write('Perfil no autorizado');              
            //         $response->withStatus(401);
            //         return $response;
            //     }
            // }
            // else
            // {
            //     $response->getBody()->write('No existe usuario');              
            //     $response->withStatus(405);
            //     return $response;
            // }
             $response->getBody()->write('Bienvenido : Credenciales validas');      
             $response = $handler->handle($request);                  
        }
        return $response;
    }

  

}
