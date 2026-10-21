<?php

require_once __DIR__ . '/HistorialDispositivoDAO.php';

/**
 * Unifica las operaciones de acceso a datos relacionadas con dispositivos.
 */
class DispositivoDAO
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión activa con la base de datos. */
    public function __construct(PDO $conexion, private string $actor = '')
    {
        $this->conexion = $conexion;
    }

    /** @return array Dispositivos registrados. */
    public function listarDispositivos(bool $retirados = false): array
    {
        $sql = "
            SELECT
                d.idLaboratorio AS idLab,
                l.nombre AS laboratorio,
                d.numeroDispositivo,
                d.modificaciones,
                d.ultimoCambio,
                d.estado
            FROM DISPOSITIVO AS d
            INNER JOIN LABORATORIO AS l
                ON l.idLaboratorio = d.idLaboratorio
            WHERE d.eliminado = :eliminado
            ORDER BY d.idLaboratorio, d.numeroDispositivo
        ";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute(['eliminado' => $retirados ? 1 : 0]);
        $dispositivos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = null;

        return $dispositivos;
    }

    /**
     * Recupera un dispositivo por su laboratorio y número.
     * @param string $idLaboratorio Identificador del laboratorio.
     * @param string $numeroDispositivo Número del dispositivo.
     * @return array|null Dispositivo encontrado o null.
     */
    public function listarDispositivo(string $idLaboratorio, string $numeroDispositivo): ?array
    {
        $consulta = $this->conexion->prepare(
            "SELECT idLaboratorio AS idLab, numeroDispositivo,
                modificaciones, ultimoCambio, estado
            FROM DISPOSITIVO
            WHERE idLaboratorio = :idLaboratorio
                AND numeroDispositivo = :numeroDispositivo AND eliminado = FALSE"
        );
        $consulta->execute([
            "idLaboratorio" => $idLaboratorio,
            "numeroDispositivo" => $numeroDispositivo
        ]);
        $dispositivo = $consulta->fetch(PDO::FETCH_ASSOC);
        return $dispositivo === false ? null : $dispositivo;
    }

    /**
     * Comprueba si existe un laboratorio.
     * @param string $idLaboratorio Identificador del laboratorio.
     * @return bool True si existe.
     */
    public function existeLaboratorio(string $idLaboratorio): bool
    {
        $consulta = $this->conexion->prepare(
            "SELECT 1 FROM LABORATORIO WHERE idLaboratorio = :idLaboratorio"
        );
        $consulta->execute(["idLaboratorio" => $idLaboratorio]);

        return $consulta->fetchColumn() !== false;
    }

    /**
     * Comprueba si existe una modificación del catálogo.
     * @param string $modificacion Código de modificación.
     * @return bool True si existe.
     */
    public function existeModificacion(string $modificacion): bool
    {
        $consulta = $this->conexion->prepare(
            "SELECT 1 FROM MODIFICACION_DISPOSITIVO
            WHERE codigoModificacion = :modificacion"
        );
        $consulta->execute(["modificacion" => $modificacion]);

        return $consulta->fetchColumn() !== false;
    }

    /**
     * Registra un dispositivo sin modificaciones iniciales.
     * @param string $idLaboratorio Laboratorio del dispositivo.
     * @param string $numeroDispositivo Número del dispositivo.
     * @param string $ultimoCambio Fecha y hora del alta.
     * @param bool $estado Estado del dispositivo.
     * @return bool True si se registró.
     */
    public function registrarDispositivo(
        string $idLaboratorio,
        string $numeroDispositivo,
        string $ultimoCambio,
        bool $estado
    ): bool {
        try {
            $this->conexion->beginTransaction();
            $existente = $this->conexion->prepare('SELECT eliminado FROM DISPOSITIVO WHERE idLaboratorio=:laboratorio AND numeroDispositivo=:numero FOR UPDATE');
            $existente->execute(['laboratorio'=>$idLaboratorio, 'numero'=>$numeroDispositivo]);
            $retirado = $existente->fetchColumn();
            if ($retirado !== false) {
                throw new ErrorHttp((bool) $retirado
                    ? 'El dispositivo fue dado de baja definitivamente. No puede recuperarse ni reutilizarse su identificación en este laboratorio. Para un equipo nuevo utilice otra identificación, por ejemplo PC016.1 (máximo 8 caracteres)'
                    : 'El dispositivo ya está registrado', 409);
            }
            $consulta = $this->conexion->prepare(
                "INSERT INTO DISPOSITIVO (
                    idLaboratorio, numeroDispositivo,
                    modificaciones, ultimoCambio, estado
                ) VALUES (
                    :idLaboratorio, :numeroDispositivo,
                    :modificaciones, :ultimoCambio, :estado
                )"
            );

            $consulta->execute([
                "idLaboratorio" => $idLaboratorio,
                "numeroDispositivo" => $numeroDispositivo,
                "modificaciones" => "N/A",
                "ultimoCambio" => $ultimoCambio,
                "estado" => $estado ? 1 : 0
            ]);
            (new HistorialDispositivoDAO($this->conexion))->registrar($idLaboratorio, $numeroDispositivo, 'ALTA', $this->actor, [], ['modificaciones' => 'N/A', 'estado' => $estado, 'ultimoCambio' => $ultimoCambio]);
            return $this->conexion->commit();
        } catch (Throwable $error) {
            if ($this->conexion->inTransaction()) $this->conexion->rollBack();
            if ($error instanceof PDOException) ErrorSql::traducir($error, 'El dispositivo ya está registrado');
            throw $error;
        }
    }

    /**
     * Modifica los datos editables de un dispositivo.
     * @param string $idLaboratorio Laboratorio del dispositivo.
     * @param string $numeroDispositivo Número del dispositivo.
     * @param string $modificaciones Modificación registrada.
     * @param string $ultimoCambio Fecha y hora de modificación.
     * @param bool $estado Estado del dispositivo.
     * @return bool True si la consulta se ejecutó correctamente.
     */
    public function modificarDispositivo(
        string $idLaboratorio,
        string $numeroDispositivo,
        string $modificaciones,
        string $ultimoCambio,
        bool $estado
    ): bool {
        try {
            $this->conexion->beginTransaction();
            $antes = $this->bloquearDispositivo($idLaboratorio, $numeroDispositivo);
            $consulta = $this->conexion->prepare(
                "UPDATE DISPOSITIVO
                SET modificaciones = :modificaciones,
                    ultimoCambio = :ultimoCambio,
                    estado = :estado
                WHERE idLaboratorio = :idLaboratorio
                    AND numeroDispositivo = :numeroDispositivo AND eliminado = FALSE"
            );
            $consulta->execute([
                "idLaboratorio" => $idLaboratorio,
                "numeroDispositivo" => $numeroDispositivo,
                "modificaciones" => $modificaciones,
                "ultimoCambio" => $ultimoCambio,
                "estado" => $estado ? 1 : 0
            ]);

            $despues = ['modificaciones' => $modificaciones, 'estado' => $estado, 'ultimoCambio' => $ultimoCambio];
            if ($antes['modificaciones'] !== $modificaciones || (bool) $antes['estado'] !== $estado || $antes['ultimoCambio'] !== $ultimoCambio) {
                (new HistorialDispositivoDAO($this->conexion))->registrar($idLaboratorio, $numeroDispositivo, 'MODIFICACION', $this->actor, $antes, $despues);
            }
            return $this->conexion->commit();
        } catch (Throwable $error) {
            if ($this->conexion->inTransaction()) $this->conexion->rollBack();
            if ($error instanceof PDOException) ErrorSql::traducir($error, 'El dispositivo ya está registrado');
            throw $error;
        }
    }

    /**
     * Retira definitivamente del inventario un dispositivo sin tickets abiertos y conserva su historial.
     * @param string $idLaboratorio Laboratorio del dispositivo.
     * @param string $numeroDispositivo Número del dispositivo.
     * @return bool True si se registró la baja lógica.
     */
    public function eliminarDispositivo(
        string $idLaboratorio,
        string $numeroDispositivo
    ): bool {
        try {
            $this->conexion->beginTransaction();
            $parametros = ['idLaboratorio' => $idLaboratorio, 'numeroDispositivo' => $numeroDispositivo];
            $antes = $this->bloquearDispositivo($idLaboratorio, $numeroDispositivo);
            $tickets = $this->conexion->prepare(
                "SELECT id FROM TICKET WHERE idLaboratorio = :idLaboratorio
                AND numeroDispositivo = :numeroDispositivo
                AND UPPER(TRIM(estado)) <> 'RESUELTO' FOR UPDATE"
            );
            $tickets->execute($parametros);
            if ($tickets->fetchColumn() !== false) {
                throw new ErrorHttp('No se puede dar de baja el dispositivo porque tiene tickets pendientes o en proceso. Resuélvalos antes de darlo de baja', 409);
            }
            $consulta = $this->conexion->prepare(
                "UPDATE DISPOSITIVO SET eliminado = TRUE, estado = FALSE,
                    ultimoCambio = ultimoCambio
                WHERE idLaboratorio = :idLaboratorio
                    AND numeroDispositivo = :numeroDispositivo"
            );
            $consulta->execute([
                "idLaboratorio" => $idLaboratorio,
                "numeroDispositivo" => $numeroDispositivo
            ]);

            if ($consulta->rowCount() !== 1) throw new ErrorHttp('El dispositivo no existe', 404);
            (new HistorialDispositivoDAO($this->conexion))->registrar($idLaboratorio, $numeroDispositivo, 'BAJA', $this->actor, $antes, ['modificaciones' => $antes['modificaciones'], 'estado' => false, 'ultimoCambio' => $antes['ultimoCambio']]);
            return $this->conexion->commit();
        } catch (Throwable $error) {
            if ($this->conexion->inTransaction()) $this->conexion->rollBack();
            if ($error instanceof PDOException) ErrorSql::traducir($error, 'El dispositivo ya está registrado');
            throw $error;
        }
    }

    private function bloquearDispositivo(string $laboratorio, string $numero): array
    {
        $consulta = $this->conexion->prepare('SELECT modificaciones,estado,eliminado,ultimoCambio FROM DISPOSITIVO
            WHERE idLaboratorio=:laboratorio AND numeroDispositivo=:numero FOR UPDATE');
        $consulta->execute(['laboratorio' => $laboratorio, 'numero' => $numero]);
        $equipo = $consulta->fetch(PDO::FETCH_ASSOC);
        if ($equipo === false) throw new ErrorHttp('El dispositivo no existe', 404);
        if ((bool) $equipo['eliminado']) throw new ErrorHttp('El dispositivo fue dado de baja definitivamente y no admite cambios', 409);
        return $equipo;
    }
}
