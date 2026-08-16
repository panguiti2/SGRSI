<?php

/**
 * Clase de acceso a datos para consultar incidencias.
 */
class AccesoDatosIncidencia
{
    private PDO $conexion;

    /**
     * Inicializa el acceso a incidencias con una conexión activa.
     * @param PDO $conexion Conexión a la base de datos.
     */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Lista todas las incidencias o las creadas por un solicitante.
     * @param string|null $cedulaSolicitante Cédula utilizada para filtrar; NULL obtiene todas.
     * @return array Incidencias recuperadas como arreglos asociativos.
     */
    public function listarIncidencias(?string $cedulaSolicitante = null): array
    {
        $sql = "SELECT idIncidencia, cedulaSolicitante, laboratorio, turno, fechaHora,
                       docente, grupo, asignatura, reportoAlumno, nombreAlumno, maquina,
                       recurso, tipoIncidencia, descripcion, vencimiento, estado,
                       urgencia, tecnicoAsignado FROM INCIDENCIA";
        if ($cedulaSolicitante !== null) {
            $sql .= " WHERE cedulaSolicitante = :cedulaSolicitante";
        }
        $sql .= " ORDER BY fechaHora DESC";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($cedulaSolicitante === null ? [] : ["cedulaSolicitante" => $cedulaSolicitante]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
