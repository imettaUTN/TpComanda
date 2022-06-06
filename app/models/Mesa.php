<?php

class Mesa
{
    public $numero;
    public $idMesa;
    public $estado;

    public function crearMesa()
    {
        //por ahora harcodeo, despues pasar a un enum 
        $estadoEsperandoPedido = 1;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO comanda (numero, estado)
         VALUES (:numero, :estado)");
        $consulta->bindValue(':numero', $this->numero, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estadoEsperandoPedido);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerMesa($numeroMesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, numero , estado FROM Mesa WHERE numero = :numero");
        $consulta->bindValue(':numero', $numeroMesa);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
    public static function obtenerMesas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT idMesa, numero , estado FROM Mesa");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

}