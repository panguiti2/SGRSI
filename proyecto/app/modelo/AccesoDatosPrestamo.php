<?php

class AccesoDatosPrestamo
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function listarPrestamos(): array
    {
        $sql = "
            SELECT
                p.idPrestamo,
                p.cedulaSolicitante,
                p.turno,
                p.nombreSolicitante,
                p.numeroLaptop,
                p.fechaRetiro,
                p.fechaDevolucion
            FROM PRESTAMO AS p
            ORDER BY p.fechaRetiro DESC
        ";

        $consulta = $this->conexion->query($sql);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
