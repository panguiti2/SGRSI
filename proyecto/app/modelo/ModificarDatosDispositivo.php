<?php

class ModificarDatosDispositivo
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

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
