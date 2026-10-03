<?php

/** Establece la conexión PDO usando la configuración del sistema. */
class ConectorPDO
{
    private string $servidor;
    private string $usuario;
    private string $clave;
    private string $baseDatos;
    private ?PDO $conexion = null;

    /** Recibe los datos de conexión cargados desde el archivo .env. */
    public function __construct(string $servidor, string $usuario, string $clave, string $baseDatos)
    {
        $this->servidor = $servidor;
        $this->usuario = $usuario;
        $this->clave = $clave;
        $this->baseDatos = $baseDatos;
    }

    /** Devuelve la conexión o lanza una excepción que atenderá el endpoint. */
    public function establecerConexion(): PDO
    {
        $this->conexion = new PDO(
            "mysql:host=$this->servidor;dbname=$this->baseDatos;charset=utf8",
            $this->usuario,
            $this->clave
        );
        $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->conexion;
    }

    /** Libera la conexión mantenida por el conector. */
    public function desconectar(): void
    {
        $this->conexion = null;
    }
}
