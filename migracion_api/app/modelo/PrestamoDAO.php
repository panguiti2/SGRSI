<?php

/** Unifica las operaciones de acceso a datos relacionadas con préstamos. */
class PrestamoDAO
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión activa con la base de datos. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /** @return array Préstamos registrados. */
    public function listarPrestamos(): array
    {
        $consulta = $this->conexion->query(
            "SELECT
                idPrestamo,
                cedulaSolicitante,
                turno,
                nombreSolicitante,
                numeroLaptop,
                fechaRetiro,
                fechaDevolucion,
                fechaDevolucionReal,
                estado
            FROM PRESTAMO
            ORDER BY fechaRetiro DESC"
        );

        $prestamos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = null;

        return $prestamos;
    }

    /**
     * Comprueba si existe un turno.
     * @param string $turno Código del turno.
     * @return bool True si existe.
     */
    public function existeTurno(string $turno): bool
    {
        $consulta = $this->conexion->prepare(
            "SELECT 1 FROM TURNO WHERE codigoTurno = :turno"
        );
        $consulta->execute(["turno" => $turno]);

        return $consulta->fetchColumn() !== false;
    }

    /**
     * Registra un préstamo activo.
     * @param string $idPrestamo Identificador del préstamo.
     * @param string $cedulaSolicitante Cédula de quien solicita.
     * @param string $turno Turno del préstamo.
     * @param string $nombreSolicitante Nombre de quien solicita.
     * @param string $numeroLaptop Número de laptop.
     * @param string $fechaRetiro Fecha y hora de retiro.
     * @param string $fechaDevolucion Fecha esperada de devolución.
     * @return bool True si se registró.
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
            $consulta = $this->conexion->prepare(
                "INSERT INTO PRESTAMO (
                    idPrestamo, cedulaSolicitante, turno,
                    nombreSolicitante, numeroLaptop,
                    fechaRetiro, fechaDevolucion
                ) VALUES (
                    :idPrestamo, :cedulaSolicitante, :turno,
                    :nombreSolicitante, :numeroLaptop,
                    :fechaRetiro, :fechaDevolucion
                )"
            );

            return $consulta->execute([
                "idPrestamo" => $idPrestamo,
                "cedulaSolicitante" => $cedulaSolicitante,
                "turno" => $turno,
                "nombreSolicitante" => $nombreSolicitante,
                "numeroLaptop" => $numeroLaptop,
                "fechaRetiro" => $fechaRetiro,
                "fechaDevolucion" => $fechaDevolucion
            ]);
        } catch (PDOException $error) {
            return false;
        }
    }

    /**
     * Registra la devolución real y cierra el préstamo.
     * @param string $idPrestamo Identificador del préstamo.
     * @param string $fechaDevolucionReal Fecha y hora real de devolución.
     * @return bool True si se cerró un préstamo activo.
     */
    public function registrarDevolucion(
        string $idPrestamo,
        string $fechaDevolucionReal
    ): bool {
        try {
            $consulta = $this->conexion->prepare(
                "UPDATE PRESTAMO
                SET fechaDevolucionReal = :fechaDevolucionReal,
                    estado = 'CERRADO'
                WHERE idPrestamo = :idPrestamo
                    AND estado = 'ACTIVO'"
            );
            $consulta->execute([
                "idPrestamo" => $idPrestamo,
                "fechaDevolucionReal" => $fechaDevolucionReal
            ]);

            return $consulta->rowCount() === 1;
        } catch (PDOException $error) {
            return false;
        }
    }
}
