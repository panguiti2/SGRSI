<?php

require_once RUTA_MODELO . '/ConectorPDO.php';

class Conexion
{
    public static function conectar(): PDO
    {
        return ConectorPDO::obtenerInstancia()->establecerConexion();
    }

    public static function desconectar(): void
    {
        ConectorPDO::obtenerInstancia()->desconectar();
    }
}
