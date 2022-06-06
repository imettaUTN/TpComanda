<?php
require_once './Pedido.php';

class Pedido
{   public $idComanda;
    public $descripcion;
    public $tiempoPedido;
    public $estado;

    public  function crearPedido()
    {
        //pasa a enum despues
        $estadoEnPreparacion = 1;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (descripcion, id_comanda, tiempoPedido , estado)
         VALUES (:descripcion, :id_comanda, :tiempoPedido, :estado)");
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estadoEnPreparacion);
        $consulta->bindValue(':codigoComanda', generate_string(5));
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public  function AsignarPedido($idPedido, $idUsuario)
    {
        //pasa a enum despues
        $estadoEnPreparacion = 1;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidosusuario (idUsuario , IdPedido )
         VALUES (:idUsuario , :IdPedido )");
        $consulta->bindValue(':idUsuario', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':IdPedido', $this->idMesa, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function obtenerPedido($codigoComanda, $descripcionPedido)
    {
        $comanda = obtenerComanda($codigoComanda);

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_comanda, descripcion,tiempoPedido , estado FROM pedido WHERE id_comanda = :id_comanda and descripcion :descripcion");
        $consulta->bindValue(':id_comanda', $comanda->id);
        $consulta->bindValue(':descripcion', $descripcionPedido);
        $consulta->execute();
        return $consulta->fetchObject('Pedido');
    }

}