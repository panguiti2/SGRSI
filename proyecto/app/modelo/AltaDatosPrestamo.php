<?php

class AltaDatosPrestamo
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function registrarPrestamo(
        string $idPrestamo,
        string $cedulaSolicitante,
        string $turno,
        string $nombreSolicitante,
        string $numeroLaptop,
        string $fechaRetiro,
        string $fechaDevolucion
    ): bool {
        try {
            $sql = "
                INSERT INTO PRESTAMO (
                    idPrestamo, cedulaSolicitante, turno,
                    nombreSolicitante, numeroLaptop, fechaRetiro, fechaDevolucion
                ) VALUES (
                    :idPrestamo, :cedulaSolicitante, :turno,
                    :nombreSolicitante, :numeroLaptop, :fechaRetiro, :fechaDevolucion
                )
            ";

            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([
                "idPrestamo" => $idPrestamo,
                "cedulaSolicitante" => $cedulaSolicitante,
                "turno" => $turno,
                "nombreSolicitante" => $nombreSolicitante,
                "numeroLaptop" => $numeroLaptop,
                "fechaRetiro" => $fechaRetiro,
                "fechaDevolucion" => $fechaDevolucion
            ]);

            return true;
        } catch (PDOException $error) {
            return false;
        }
    }
}
