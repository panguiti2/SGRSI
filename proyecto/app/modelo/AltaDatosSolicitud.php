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

    /**
     * Actualiza el estado de una solicitud existente.
     * @param string $idSolicitud Identificador de la solicitud.
     * @param string $estado Nuevo estado de seguimiento.
     * @return bool TRUE si la consulta se ejecuta correctamente.
     */
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
