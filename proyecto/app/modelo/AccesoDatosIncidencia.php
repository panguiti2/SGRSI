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
        $sql = "SELECT t.id AS idIncidencia, t.cedulaSolicitante, t.laboratorio,
                       i.turno, t.fechaHora, t.NombreDocente AS docente, i.grupo,
                       i.asignatura, i.reportoAlumno, i.NombreAlumno AS nombreAlumno,
                       i.maquina, i.recurso, i.tipoIncidencia, i.descripcion,
                       i.vencimiento, t.estado, i.urgencia, i.tecnicoAsignado
                FROM TICKET AS t
                INNER JOIN INCIDENCIA AS i ON i.id = t.id";
        if ($cedulaSolicitante !== null) {
            $sql .= " WHERE t.cedulaSolicitante = :cedulaSolicitante";
        }
        $sql .= " ORDER BY t.fechaHora DESC";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($cedulaSolicitante === null ? [] : ["cedulaSolicitante" => $cedulaSolicitante]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
