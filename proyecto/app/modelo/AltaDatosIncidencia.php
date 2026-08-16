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
                        id, cedulaSolicitante, fechaApertura, grupo, nombreDocente,
                        descripcion, turno, estado, asignatura
                    ) VALUES (
                        :id, :cedulaSolicitante, :fechaApertura, :grupo, :nombreDocente,
                        :descripcion, :turno, 'PENDIENTE', :asignatura
                    )";
            $consultaTicket = $this->conexion->prepare($sqlTicket);
            $consultaTicket->execute([
                "id" => $incidencia["idIncidencia"],
                "cedulaSolicitante" => $incidencia["cedulaSolicitante"],
                "fechaApertura" => $incidencia["fechaApertura"],
                "grupo" => $incidencia["grupo"],
                "nombreDocente" => $incidencia["nombreDocente"],
                "descripcion" => $incidencia["descripcion"],
                "turno" => $incidencia["turno"],
                "asignatura" => $incidencia["asignatura"]
            ]);

            $sqlIncidencia = "INSERT INTO INCIDENCIA (id, reportoAlumno, nombreAlumno)
                              VALUES (:id, :reportoAlumno, :nombreAlumno)";
            $consultaIncidencia = $this->conexion->prepare($sqlIncidencia);
            $consultaIncidencia->execute([
                "id" => $incidencia["idIncidencia"],
                "reportoAlumno" => $incidencia["reportoAlumno"],
                "nombreAlumno" => $incidencia["nombreAlumno"]
            ]);
            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) $this->conexion->rollBack();
            return false;
        }
    }

    /**
     * Actualiza el estado y registra al técnico que gestiona la incidencia.
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

            $consultaGestion = $this->conexion->prepare(
                "INSERT INTO GESTIONA (idTicket, cedulaTecnico, fecha)
                 VALUES (:idTicket, :cedulaTecnico, NOW())
                 ON DUPLICATE KEY UPDATE cedulaTecnico = VALUES(cedulaTecnico), fecha = NOW()"
            );
            $consultaGestion->execute([
                "idTicket" => $asignacion["idIncidencia"],
                "cedulaTecnico" => $asignacion["cedulaTecnico"]
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
