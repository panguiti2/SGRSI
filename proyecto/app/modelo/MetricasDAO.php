<?php

/** Recupera los indicadores básicos utilizados por el módulo de métricas. */
class MetricasDAO
{
    private PDO $conexion;

    /** Recibe la conexión PDO utilizada para calcular los indicadores. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /** @return array Indicadores actuales del sistema. */
    public function obtenerMetricas(): array
    {
        $consulta = $this->conexion->query(
            "SELECT
                (SELECT COUNT(*) FROM USUARIO WHERE estado = TRUE) AS usuariosActivos,
                (SELECT COUNT(*) FROM TICKET WHERE estado <> 'RESUELTO') AS ticketsAbiertos,
                (SELECT COUNT(*) FROM TICKET AS t INNER JOIN INCIDENCIA AS i ON i.id = t.id
                    WHERE t.estado <> 'RESUELTO') AS incidenciasAbiertas,
                (SELECT COUNT(*) FROM TICKET AS t INNER JOIN SERVICIO AS s ON s.idServicio = t.id
                    WHERE t.estado <> 'RESUELTO') AS solicitudesAbiertas,
                (SELECT COUNT(*) FROM PRESTAMO WHERE estado = 'ACTIVO') AS prestamosPendientes,
                (SELECT COUNT(*) FROM DISPOSITIVO WHERE estado = TRUE AND eliminado = FALSE) AS dispositivosActivos,
                (SELECT COUNT(*) FROM REGISTRO_USO) AS registrosUso"
        );

        $metricas = $consulta->fetch(PDO::FETCH_ASSOC);
        $metricas = $metricas === false ? [] : $metricas;
        $consultaLaboratorios = $this->conexion->query(
            "SELECT l.idLaboratorio, l.nombre,
                (SELECT COUNT(*) FROM DISPOSITIVO d WHERE d.idLaboratorio = l.idLaboratorio AND d.estado = TRUE AND d.eliminado = FALSE) AS dispositivosActivos,
                (SELECT COUNT(*) FROM TICKET t INNER JOIN INCIDENCIA i ON i.id = t.id WHERE t.idLaboratorio = l.idLaboratorio AND t.estado <> 'RESUELTO') AS incidenciasAbiertas,
                (SELECT COUNT(*) FROM TICKET t INNER JOIN SERVICIO s ON s.idServicio = t.id WHERE t.idLaboratorio = l.idLaboratorio AND t.estado <> 'RESUELTO') AS solicitudesAbiertas,
                (SELECT COUNT(*) FROM REGISTRO_USO r WHERE r.idLaboratorio = l.idLaboratorio) AS registrosUso
            FROM LABORATORIO l ORDER BY l.idLaboratorio"
        );
        $metricas['porLaboratorio'] = $consultaLaboratorios->fetchAll(PDO::FETCH_ASSOC);
        return $metricas;
    }
}
