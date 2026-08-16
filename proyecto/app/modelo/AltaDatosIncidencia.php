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
            $sqlTicket = "INSERT INTO TICKET (
                        id, cedulaSolicitante, laboratorio, fechaHora, NombreDocente, estado
                    ) VALUES (
                        :id, :cedulaSolicitante, :laboratorio, :fechaHora, :NombreDocente, 'PENDIENTE'
                    )";
            $consultaTicket = $this->conexion->prepare($sqlTicket);
            $consultaTicket->execute([
                "id" => $incidencia["idIncidencia"],
                "cedulaSolicitante" => $incidencia["cedulaSolicitante"],
                "laboratorio" => $incidencia["laboratorio"],
                "fechaHora" => $incidencia["fechaHora"],
                "NombreDocente" => $incidencia["docente"]
            ]);

            $sqlIncidencia = "INSERT INTO INCIDENCIA (
                        id, descripcion, turno, asignatura, reportoAlumno,
                        NombreAlumno, grupo, maquina, recurso, tipoIncidencia
                    ) VALUES (
                        :id, :descripcion, :turno, :asignatura, :reportoAlumno,
                        :NombreAlumno, :grupo, :maquina, :recurso, :tipoIncidencia
                    )";
            $consultaIncidencia = $this->conexion->prepare($sqlIncidencia);
            $consultaIncidencia->execute([
                "id" => $incidencia["idIncidencia"],
                "descripcion" => $incidencia["descripcion"],
                "turno" => $incidencia["turno"],
                "asignatura" => $incidencia["asignatura"],
                "reportoAlumno" => $incidencia["reportoAlumno"],
                "NombreAlumno" => $incidencia["nombreAlumno"],
                "grupo" => $incidencia["grupo"],
                "maquina" => $incidencia["maquina"],
                "recurso" => $incidencia["recurso"],
                "tipoIncidencia" => $incidencia["tipoIncidencia"]
            ]);
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
        try {
            $this->conexion->beginTransaction();
            $consultaTicket = $this->conexion->prepare(
                "UPDATE TICKET
                 SET estado = :estado,
                     FechaCierre = CASE WHEN :estadoCierre = 'RESUELTO' THEN NOW() ELSE NULL END
                 WHERE id = :idIncidencia"
            );
            $consultaTicket->execute([
                "estado" => $asignacion["estado"],
                "estadoCierre" => $asignacion["estado"],
                "idIncidencia" => $asignacion["idIncidencia"]
            ]);

            $consultaIncidencia = $this->conexion->prepare(
                "UPDATE INCIDENCIA
                 SET vencimiento = :vencimiento, urgencia = :urgencia,
                     tecnicoAsignado = :tecnicoAsignado
                 WHERE id = :idIncidencia"
            );
            $consultaIncidencia->execute([
                "vencimiento" => $asignacion["vencimiento"],
                "urgencia" => $asignacion["urgencia"],
                "tecnicoAsignado" => $asignacion["tecnicoAsignado"],
                "idIncidencia" => $asignacion["idIncidencia"]
            ]);
            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            return false;
        }
    }
}
