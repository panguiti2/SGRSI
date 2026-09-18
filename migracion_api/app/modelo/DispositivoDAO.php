<?php

/**
 * Unifica las operaciones de acceso a datos relacionadas con dispositivos.
 */
class DispositivoDAO
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión activa con la base de datos. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /** @return array Dispositivos registrados. */
    public function listarDispositivos(): array
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
            ORDER BY d.idLaboratorio, d.numeroDispositivo
        ";

        $consulta = $this->conexion->query($sql);
        $dispositivos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = null;

        return $dispositivos;
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
            $consulta = $this->conexion->prepare(
                "INSERT INTO DISPOSITIVO (
                    idLaboratorio, numeroDispositivo,
                    modificaciones, ultimoCambio, estado
                ) VALUES (
                    :idLaboratorio, :numeroDispositivo,
                    :modificaciones, :ultimoCambio, :estado
                )"
            );

            return $consulta->execute([
                "idLaboratorio" => $idLaboratorio,
                "numeroDispositivo" => $numeroDispositivo,
                "modificaciones" => "N/A",
                "ultimoCambio" => $ultimoCambio,
                "estado" => $estado
            ]);
        } catch (PDOException $error) {
            return false;
        }
    }

    /**
     * Modifica los datos editables de un dispositivo.
     * @param string $idLaboratorio Laboratorio del dispositivo.
     * @param string $numeroDispositivo Número del dispositivo.
     * @param string $modificaciones Modificación registrada.
     * @param string $ultimoCambio Fecha y hora de modificación.
     * @param bool $estado Estado del dispositivo.
     * @return bool True si se modificó una fila.
     */
    public function modificarDispositivo(
        string $idLaboratorio,
        string $numeroDispositivo,
        string $modificaciones,
        string $ultimoCambio,
        bool $estado
    ): bool {
        try {
            $consulta = $this->conexion->prepare(
                "UPDATE DISPOSITIVO
                SET modificaciones = :modificaciones,
                    ultimoCambio = :ultimoCambio,
                    estado = :estado
                WHERE idLaboratorio = :idLaboratorio
                    AND numeroDispositivo = :numeroDispositivo"
            );
            $consulta->execute([
                "idLaboratorio" => $idLaboratorio,
                "numeroDispositivo" => $numeroDispositivo,
                "modificaciones" => $modificaciones,
                "ultimoCambio" => $ultimoCambio,
                "estado" => $estado
            ]);

            return $consulta->rowCount() === 1;
        } catch (PDOException $error) {
            return false;
        }
    }

    /**
     * Elimina un dispositivo que no esté asociado a otros datos.
     * @param string $idLaboratorio Laboratorio del dispositivo.
     * @param string $numeroDispositivo Número del dispositivo.
     * @return bool True si se eliminó una fila.
     */
    public function eliminarDispositivo(
        string $idLaboratorio,
        string $numeroDispositivo
    ): bool {
        try {
            $consulta = $this->conexion->prepare(
                "DELETE FROM DISPOSITIVO
                WHERE idLaboratorio = :idLaboratorio
                    AND numeroDispositivo = :numeroDispositivo"
            );
            $consulta->execute([
                "idLaboratorio" => $idLaboratorio,
                "numeroDispositivo" => $numeroDispositivo
            ]);

            return $consulta->rowCount() === 1;
        } catch (PDOException $error) {
            return false;
        }
    }
}
