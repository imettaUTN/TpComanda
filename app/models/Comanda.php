<?php

class Comanda
{   
    public $id;
    public $codigoComanda;
    public $nombreCliente;
    public $idMesa;
    public $estado;


    private function generate_string($strength =5) 
    {
        $input_length = strlen('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
    
        return $random_string;
    }

    public function crearComanda()
    {
        //pasa a enum despues
        $estadoEnPreparacion = 1;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO comanda (nombreCliente, idMesa, estado , codigoComanda)
         VALUES (:nombreCliente, :idMesa, :estado, :codigoComanda)");
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estadoEnPreparacion);
        $consulta->bindValue(':codigoComanda', generate_string(5));
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerComanda($codigoComanda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id,codigoComanda,nombreCliente, idMesa, estado FROM comanda WHERE codigoComanda = :codigoComanda");
        $consulta->bindValue(':codigoComanda', $codigoComanda);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Comanda');
    }

}