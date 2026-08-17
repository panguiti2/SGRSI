<?php

class BajaDatosDispositivo
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function eliminarDispositivo(string $idLaboratorio, string $numeroDispositivo): bool
    {
        try {
            $this->conexion->beginTransaction();

            $consulta = $this->conexion->prepare(
                "DELETE FROM DISPOSITIVO
                WHERE idLaboratorio = :idLaboratorio
                    AND numeroDispositivo = :numeroDispositivo"
            );
            $consulta->execute([
                "idLaboratorio" => $idLaboratorio,
                "numeroDispositivo" => $numeroDispositivo
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
