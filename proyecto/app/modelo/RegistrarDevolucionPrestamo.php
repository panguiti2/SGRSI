<?php

/** Registra la devolución real y cierra un préstamo activo. */
class RegistrarDevolucionPrestamo
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión PDO activa. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Guarda la fecha de devolución real y cambia el préstamo a CERRADO.
     * @param string $idPrestamo Identificador del préstamo.
     * @param string $fechaDevolucion Fecha y hora efectiva de devolución.
     * @return bool True si se cerró el préstamo.
     */
    public function registrarDevolucion(string $idPrestamo, string $fechaDevolucion): bool
    {
        try {
            $consulta = $this->conexion->prepare(
                "UPDATE PRESTAMO
                SET fechaDevolucionReal = :fechaDevolucion,
                    estado = 'CERRADO'
                WHERE idPrestamo = :idPrestamo
                    AND estado = 'ACTIVO'"
            );
            $consulta->execute([
                "idPrestamo" => $idPrestamo,
                "fechaDevolucion" => $fechaDevolucion
            ]);

            return $consulta->rowCount() === 1;
        } catch (PDOException $error) {
            return false;
        }
    }
}
