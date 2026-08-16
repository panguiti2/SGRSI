<?php

class AltaDatosSolicitud
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function registrarSolicitud(array $solicitud): bool
    {
        try {
            $this->conexion->beginTransaction();
            $sql = "INSERT INTO SOLICITUD (
                        idSolicitud, cedulaSolicitante, laboratorio, turno, docente,
                        asignatura, email, fechaHora, tipoServicio, software,
                        todasMaquinas, prioridad, descripcion
                    ) VALUES (
                        :idSolicitud, :cedulaSolicitante, :laboratorio, :turno, :docente,
                        :asignatura, :email, :fechaHora, :tipoServicio, :software,
                        :todasMaquinas, :prioridad, :descripcion
                    )";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute($solicitud);
            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            return false;
        }
    }

    public function actualizarEstado(string $idSolicitud, string $estado): bool
    {
        $consulta = $this->conexion->prepare(
            "UPDATE SOLICITUD SET estado = :estado WHERE idSolicitud = :idSolicitud"
        );
        return $consulta->execute([
            "idSolicitud" => $idSolicitud,
            "estado" => $estado
        ]);
    }
}
