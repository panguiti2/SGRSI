<?php

/**
 * Clase encargada de registrar solicitudes y actualizar su estado.
 */
class AltaDatosSolicitud
{
    private PDO $conexion;

    /**
     * Inicializa las operaciones de solicitudes con una conexión activa.
     * @param PDO $conexion Conexión a la base de datos.
     */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Registra una solicitud mediante una transacción.
     * @param array $solicitud Datos validados de la nueva solicitud.
     * @return bool TRUE si se confirma el registro, FALSE en caso contrario.
     */
    public function registrarSolicitud(array $solicitud): bool
    {
        try {
            $this->conexion->beginTransaction();
            $sqlTicket = "INSERT INTO TICKET (
                        id, cedulaSolicitante, idLaboratorio, numeroDispositivo, fechaApertura, grupo, nombreDocente,
                        descripcion, turno, estado, asignatura
                    ) VALUES (
                        :id, :cedulaSolicitante, :idLaboratorio, :numeroDispositivo, :fechaApertura, :grupo, :nombreDocente,
                        :descripcion, :turno, 'PENDIENTE', :asignatura
                    )";
            $consultaTicket = $this->conexion->prepare($sqlTicket);
            $consultaTicket->execute([
                "id" => $solicitud["idSolicitud"],
                "cedulaSolicitante" => $solicitud["cedulaSolicitante"],
                "idLaboratorio" => $solicitud["idLaboratorio"],
                "numeroDispositivo" => $solicitud["numeroDispositivo"],
                "fechaApertura" => $solicitud["fechaApertura"],
                "grupo" => $solicitud["grupo"],
                "nombreDocente" => $solicitud["nombreDocente"],
                "descripcion" => $solicitud["descripcion"],
                "turno" => $solicitud["turno"],
                "asignatura" => $solicitud["asignatura"]
            ]);

            $sqlServicio = "INSERT INTO SERVICIO (idServicio, tipoServicio)
                            VALUES (:idServicio, :tipoServicio)";
            $consultaServicio = $this->conexion->prepare($sqlServicio);
            $consultaServicio->execute([
                "idServicio" => $solicitud["idSolicitud"],
                "tipoServicio" => $solicitud["tipoServicio"]
            ]);
            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            return false;
        }
    }

    /**
     * Actualiza el estado de una solicitud existente.
     * @param string $idSolicitud Identificador de la solicitud.
     * @param string $estado Nuevo estado de seguimiento.
     * @return bool TRUE si la consulta se ejecuta correctamente.
     */
    public function actualizarEstado(string $idSolicitud, string $estado): bool
    {
        $consulta = $this->conexion->prepare(
            "UPDATE TICKET
             SET estado = :estado,
                 fechaCierre = CASE WHEN :estadoCierre = 'RESUELTO' THEN NOW() ELSE NULL END
             WHERE id = :idSolicitud"
        );
        return $consulta->execute([
            "idSolicitud" => $idSolicitud,
            "estado" => $estado,
            "estadoCierre" => $estado
        ]);
    }
}
