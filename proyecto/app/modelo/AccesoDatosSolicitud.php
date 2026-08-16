<?php

/**
 * Clase de acceso a datos para consultar solicitudes de servicio.
 */
class AccesoDatosSolicitud
{
    private PDO $conexion;

    /**
     * Inicializa el acceso a solicitudes con una conexión activa.
     * @param PDO $conexion Conexión a la base de datos.
     */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Lista todas las solicitudes o solamente las de un solicitante.
     * @param string|null $cedulaSolicitante Cédula utilizada para filtrar; NULL obtiene el listado completo.
     * @return array Solicitudes recuperadas como arreglos asociativos.
     */
    public function listarSolicitudes(?string $cedulaSolicitante = null): array
    {
        $sql = "SELECT idSolicitud, cedulaSolicitante, laboratorio, turno, docente,
                       asignatura, email, fechaHora, tipoServicio, software,
                       todasMaquinas, prioridad, descripcion, estado
                FROM SOLICITUD";

        if ($cedulaSolicitante !== null) {
            $sql .= " WHERE cedulaSolicitante = :cedulaSolicitante";
        }

        $sql .= " ORDER BY fechaHora DESC";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($cedulaSolicitante === null ? [] : [
            "cedulaSolicitante" => $cedulaSolicitante
        ]);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
