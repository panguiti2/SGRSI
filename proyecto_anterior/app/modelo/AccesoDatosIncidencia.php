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
        $sql = "SELECT t.id AS idIncidencia, t.cedulaSolicitante, t.fechaApertura,
                       t.fechaCierre, t.grupo, t.nombreDocente, t.descripcion,
                       t.turno, t.estado, t.asignatura, t.idLaboratorio, t.numeroDispositivo, i.reportoAlumno,
                       i.nombreAlumno, i.diagnostico, i.solucion, t.cedulaTecnico, t.fechaGestion
                FROM TICKET AS t
                INNER JOIN INCIDENCIA AS i ON i.id = t.id";
        if ($cedulaSolicitante !== null) {
            $sql .= " WHERE t.cedulaSolicitante = :cedulaSolicitante";
        }
        $sql .= " ORDER BY t.fechaApertura DESC";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($cedulaSolicitante === null ? [] : ["cedulaSolicitante" => $cedulaSolicitante]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
