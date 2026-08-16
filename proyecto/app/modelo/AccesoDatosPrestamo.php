<?php

/**
 * Consulta los préstamos registrados en la base de datos.
 */
class AccesoDatosPrestamo
{
    private PDO $conexion;

    /**
     * Inicializa el acceso a datos con una conexión activa.
     * @param PDO $conexion Conexión activa con la base de datos.
     */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Obtiene todos los préstamos ordenados por fecha de retiro.
     * @return array Préstamos encontrados.
     */
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
