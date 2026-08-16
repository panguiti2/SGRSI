<?php

/**
 * Clase encargada del alta y la asignación técnica de incidencias.
 */
class AltaDatosIncidencia
{
    private PDO $conexion;

    /**
     * Inicializa las operaciones de incidencias con una conexión activa.
     * @param PDO $conexion Conexión a la base de datos.
     */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registra una incidencia mediante una transacción.
     * @param array $incidencia Datos validados de la incidencia.
     * @return bool TRUE si se confirma el registro, FALSE en caso contrario.
     */
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

    /**
     * Guarda el vencimiento, estado, urgencia y técnico de una incidencia.
     * @param array $asignacion Datos validados de la asignación técnica.
     * @return bool TRUE si la actualización se ejecuta correctamente.
     */
    public function asignarIncidencia(array $asignacion): bool
    {
        $sql = "UPDATE INCIDENCIA
                SET vencimiento = :vencimiento, estado = :estado,
                    urgencia = :urgencia, tecnicoAsignado = :tecnicoAsignado
                WHERE idIncidencia = :idIncidencia";
        return $this->conexion->prepare($sql)->execute($asignacion);
    }
}
