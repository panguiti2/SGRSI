<?php

class RegistrarDevolucionPrestamo
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

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
