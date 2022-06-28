<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

use \App\Models\Usuario as Usuario;

class UsuarioController implements IApiUsable
{

  public function CargarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $usuario = $parametros['usuario'];
    $clave = $parametros['clave'];
    $perfil = $parametros['perfil'];

    if($usuario == null)
    {
      $response->getBody()->write("Debe ingresar un usuario para generar el usuario");
      return $response;
    }
    if($perfil == null)
    {
      $response->getBody()->write("Debe ingresar un perfil para generar el usuario");
      return $response;
    }
    if($clave == null)
    {
      $response->getBody()->write("Debe ingresar una clave para generar el usuario");
      return $response;
    }

    // Creamos el usuario
    $usr = new Usuario();
    $usr->usuario = strtoupper($usuario);
    $usr->clave = $clave;
    $usr->perfil = strtoupper($perfil);
    $usr->save();

    $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    // Buscamos usuario por nombre
    $usr = $args['usuario'];
    if($usr == null)
    {
      $response->getBody()->write("Debe ingresar un usuario para obtener el usuario");
      return $response;
    }

    if(is_numeric($usr))
    {
      // buscamos por id
      $usuario = Usuario::where('id', $usr)->first();
    }
    else
    {
      // Buscamos por attr usuario
      $usuario = Usuario::where('usuario', $usr)->first();
    }
    $payload = json_encode($usuario);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Usuario::all();
    $payload = json_encode(array("listaUsuario" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();

    $usrModificado = $parametros['usuario'];
    $usuarioId = $args['id'];

 
    if($usuarioId == null)
    {
      $response->getBody()->write("Debe ingresar el id de usuario");
      return $response;
    }
    if($usrModificado == null)
    {
      $response->getBody()->write("Debe ingresar el nuevo nombre de usuario");
      return $response;
    }
    // Conseguimos el objeto
    $usr = Usuario::where('id', '=', $usuarioId)->first();

    if($usr->perfil == 'ADMIN')
    {
      $payload = json_encode(array("mensaje" => "Usuario no autorizado"));
      $response->WithStatus(401);
    }
    else
    {
    // Si existe
    if ($usr !== null) {
      // Seteamos un nuevo usuario
      $usr->usuario = $usrModificado;
      // Guardamos en base de datos
      $usr->save();
      $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "Usuario no encontrado"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}

  public function BorrarUno($request, $response, $args)
  {
    $usuarioId = $args['id'];

    if($usuarioId == null)
    {
      $response->getBody()->write("Debe ingresar el id de usuario a borrar");
      return $response;
    }
    // Buscamos el usuario
    $usuario = Usuario::find($usuarioId);
    // Borramos
    $usuario->delete();

    $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
}
