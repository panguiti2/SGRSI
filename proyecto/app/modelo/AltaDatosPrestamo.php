<?php

/**
 * Registra préstamos de equipos en la base de datos.
 */
class AltaDatosPrestamo
{
    private PDO $conexion;

    /**
     * Inicializa el alta de préstamos con una conexión activa.
     * @param PDO $conexion Conexión activa con la base de datos.
     */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registra un préstamo con los datos recibidos.
     * @param string $idPrestamo Identificador del préstamo.
     * @param string $cedulaSolicitante Cédula del solicitante.
     * @param string $turno Turno correspondiente al préstamo.
     * @param string $nombreSolicitante Nombre del solicitante.
     * @param string $numeroLaptop Número de la laptop prestada.
     * @param string $fechaRetiro Fecha de retiro del equipo.
     * @return bool Verdadero si el préstamo fue registrado.
     */
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
