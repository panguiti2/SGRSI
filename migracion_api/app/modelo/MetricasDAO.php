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
                (SELECT COUNT(*) FROM DISPOSITIVO WHERE estado = TRUE) AS dispositivosActivos,
                (SELECT COUNT(*) FROM REGISTRO_USO) AS registrosUso"
        );

        $metricas = $consulta->fetch(PDO::FETCH_ASSOC);
        return $metricas === false ? [] : $metricas;
    }
}
