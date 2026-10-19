<?php

/** Actualiza los datos editables de un dispositivo. */
class ModificarDatosDispositivo
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión PDO activa. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Actualiza modificación, fecha de cambio y estado de un dispositivo.
     * @param string $idLaboratorio Laboratorio al que pertenece.
     * @param string $numeroDispositivo Identificador del dispositivo.
     * @param string $modificaciones Modificación registrada.
     * @param string $ultimoCambio Fecha y hora del cambio.
     * @param bool $estado Estado activo o inactivo.
     * @return bool True si se actualizó una fila.
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

            $sql = "UPDATE DISPOSITIVO
                SET modificaciones = :modificaciones,
                    ultimoCambio = :ultimoCambio,
                    estado = :estado
                WHERE idLaboratorio = :idLaboratorio
                    AND numeroDispositivo = :numeroDispositivo";

            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([
                "idLaboratorio" => $idLaboratorio,
                "numeroDispositivo" => $numeroDispositivo,
                "modificaciones" => $modificaciones,
                "ultimoCambio" => $ultimoCambio,
                "estado" => $estado
            ]);

            $this->conexion->commit();
            return $consulta->rowCount() === 1;
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }
}
