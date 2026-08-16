<?php

class AltaDatosIncidencia
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function registrarIncidencia(array $incidencia): bool
    {
        try {
            $this->conexion->beginTransaction();
            $sql = "INSERT INTO INCIDENCIA (
                        idIncidencia, cedulaSolicitante, laboratorio, turno, fechaHora,
                        docente, grupo, asignatura, reportoAlumno, nombreAlumno, maquina,
                        recurso, tipoIncidencia, descripcion
                    ) VALUES (
                        :idIncidencia, :cedulaSolicitante, :laboratorio, :turno, :fechaHora,
                        :docente, :grupo, :asignatura, :reportoAlumno, :nombreAlumno, :maquina,
                        :recurso, :tipoIncidencia, :descripcion
                    )";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute($incidencia);
            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) $this->conexion->rollBack();
            return false;
        }
    }

    public function asignarIncidencia(array $asignacion): bool
    {
        $sql = "UPDATE INCIDENCIA
                SET vencimiento = :vencimiento, estado = :estado,
                    urgencia = :urgencia, tecnicoAsignado = :tecnicoAsignado
                WHERE idIncidencia = :idIncidencia";
        return $this->conexion->prepare($sql)->execute($asignacion);
    }
}
