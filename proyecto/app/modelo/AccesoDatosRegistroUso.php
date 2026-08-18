<?php

/**
 * Consulta los registros de uso de los laboratorios.
 */
class AccesoDatosRegistroUso
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión PDO activa. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Recupera los registros. Si se recibe una cédula, devuelve solamente los de ese solicitante.
     * @param string|null $cedulaSolicitante Cédula usada para filtrar el listado.
     * @return array Registros de uso ordenados desde el más reciente.
     */
    public function listarRegistros(?string $cedulaSolicitante = null): array
    {
        $sql = "
            SELECT
                r.idRegistro,
                r.cedulaSolicitante,
                CONCAT(u.nombre, ' ', u.apellido) AS solicitante,
                l.nombre AS laboratorio,
                r.turno,
                r.fechaHora,
                r.nombreDocente,
                r.grupo,
                r.asignatura,
                r.usoMaquinas,
                r.huboIncidencias
            FROM REGISTRO_USO AS r
            INNER JOIN USUARIO AS u ON u.cedula = r.cedulaSolicitante
            INNER JOIN LABORATORIO AS l ON l.idLaboratorio = r.idLaboratorio
        ";

        if ($cedulaSolicitante !== null) {
            $sql .= " WHERE r.cedulaSolicitante = :cedulaSolicitante";
        }

        $sql .= " ORDER BY r.fechaHora DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($cedulaSolicitante === null ? [] : [
            "cedulaSolicitante" => $cedulaSolicitante
        ]);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
