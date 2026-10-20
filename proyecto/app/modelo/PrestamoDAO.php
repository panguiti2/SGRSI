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

    /** Comprueba si la cédula ya tiene un préstamo pendiente de devolución. */
    public function tienePrestamoActivo(string $cedulaSolicitante): bool
    {
        $consulta = $this->conexion->prepare(
            "SELECT 1 FROM PRESTAMO
            WHERE cedulaSolicitante = :cedulaSolicitante AND estado = 'ACTIVO'
            LIMIT 1"
        );
        $consulta->execute(["cedulaSolicitante" => $cedulaSolicitante]);

        return $consulta->fetchColumn() !== false;
    }

    /** Compara identificadores normalizados sin confundir mayúsculas o espacios exteriores. */
    public function laptopTienePrestamoActivo(string $numeroLaptop): bool
    {
        $consulta = $this->conexion->prepare(
            "SELECT 1 FROM PRESTAMO WHERE BINARY UPPER(TRIM(numeroLaptop)) = BINARY :numeroLaptop
            AND estado = 'ACTIVO' LIMIT 1"
        );
        $consulta->execute(['numeroLaptop' => mb_strtoupper(trim($numeroLaptop), 'UTF-8')]);
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
        $numeroLaptop = mb_strtoupper(trim($numeroLaptop), 'UTF-8');
        $bloqueos = ['SGRSI_PRESTAMO_' . $cedulaSolicitante,
            'SGRSI_LAPTOP_' . substr(hash('sha256', $numeroLaptop), 0, 40)];
        $adquiridos = [];
        try {
            foreach ($bloqueos as $nombre) {
                $consultaBloqueo = $this->conexion->prepare('SELECT GET_LOCK(:nombre, 5)');
                $consultaBloqueo->execute(['nombre' => $nombre]);
                if ((int) $consultaBloqueo->fetchColumn() !== 1) {
                    throw new ErrorHttp('La operación está en curso. Intente nuevamente', 409);
                }
                $adquiridos[] = $nombre;
            }
            if ($this->tienePrestamoActivo($cedulaSolicitante)) {
                throw new ErrorHttp('La cédula ya posee un préstamo activo', 409);
            }
            if ($this->laptopTienePrestamoActivo($numeroLaptop)) {
                throw new ErrorHttp('La laptop ya posee un préstamo activo. Registre su devolución antes de volver a prestarla', 409);
            }
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
            ErrorSql::traducir($error, 'El identificador del préstamo ya existe. Intente nuevamente');
        } finally {
            foreach (array_reverse($adquiridos) as $nombre) {
                $liberar = $this->conexion->prepare('SELECT RELEASE_LOCK(:nombre)');
                $liberar->execute(['nombre' => $nombre]);
            }
        }
    }

    /**
     * Registra la devolución real y cierra el préstamo.
     * Los registros cerrados quedan conservados como historial.
     * @param string $idPrestamo Identificador del préstamo.
     * @param string $fechaDevolucionReal Fecha y hora real de devolución.
     * @return bool True si se cerró un préstamo activo.
     */
    public function registrarDevolucion(
        string $idPrestamo,
        string $fechaDevolucionReal
    ): bool {
        try {
            $this->conexion->beginTransaction();
            $original = $this->conexion->prepare('SELECT estado, fechaRetiro FROM PRESTAMO WHERE idPrestamo = :id FOR UPDATE');
            $original->execute(['id' => $idPrestamo]);
            $prestamo = $original->fetch(PDO::FETCH_ASSOC);
            if (!$prestamo) throw new ErrorHttp('El préstamo no existe', 404);
            if (strtoupper($prestamo['estado']) !== 'ACTIVO') throw new ErrorHttp('El préstamo ya está cerrado', 409);
            if ($fechaDevolucionReal < $prestamo['fechaRetiro']) throw new ErrorHttp('La devolución no puede ser anterior al retiro');
            $consulta = $this->conexion->prepare(
                "UPDATE PRESTAMO
                SET fechaDevolucionReal = :fechaDevolucionReal,
                    estado = 'CERRADO'
                WHERE idPrestamo = :idPrestamo
                    AND estado = 'ACTIVO'
                    AND fechaRetiro <= :fechaVerificar"
            );
            $consulta->execute([
                "idPrestamo" => $idPrestamo,
                "fechaDevolucionReal" => $fechaDevolucionReal,
                "fechaVerificar" => $fechaDevolucionReal
            ]);

            return $this->conexion->commit();
        } catch (Throwable $error) {
            if ($this->conexion->inTransaction()) $this->conexion->rollBack();
            if ($error instanceof PDOException) ErrorSql::traducir($error, 'El identificador del préstamo ya existe. Intente nuevamente');
            throw $error;
        }
    }

}
