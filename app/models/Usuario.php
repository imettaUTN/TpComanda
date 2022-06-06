<?php

class Usuario
{   
    public $id;
    public $usuario;
    public $nombre;
    public $apellido;
    public $fechaInicio;
    public $fechaFin;
    public $sector;
    public $clave;

    public  function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuario (Nombre, Apellido, usuario, FechaInicio,IdUsuario, Sector_id, clave)
         VALUES (:Nombre, :Apellido, :usuario, :FechaInicio,:Sector_id, :clave)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $nombreUsuario = substr($this->nombre,2).$this->apellido;
        $consulta->bindValue(':Nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':Apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':usuario', nombreUsuario);
        $consulta->bindValue(':FechaInicio', date_format($fechaInicio, 'Y-m-d H:i:s'));
        $consulta->bindValue(':Sector_id', $this->sector, PARAM_INT);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, nombre, apellido, fechaInicio,fechaFin,sector,clave FROM usuario");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, nombre, apellido, fechaInicio,fechaFin,sector,clave FROM usuario WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function modificarUsuario()
    {
        // $objAccesoDato = AccesoDatos::obtenerInstancia();
        // $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave WHERE id = :id");
        // $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        // $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        // $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        // $consulta->execute();
    }

    public static function borrarUsuario($usuario)
    {
        // $objAccesoDato = AccesoDatos::obtenerInstancia();
        // $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
        // $fecha = new DateTime(date("d-m-Y"));
        // $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        // $consulta->bindValue(':fechaFin', date_format($fecha, 'Y-m-d H:i:s'));
        // $consulta->execute();
    }
}