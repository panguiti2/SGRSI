<?php

/** Recupera indicadores generales del sistema. */
class AccesoDatosMetricas
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión activa con la base de datos. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Calcula los indicadores básicos mediante consultas COUNT.
     * @return array Indicadores del sistema.
     */
    public function obtenerMetricas(): array
    {
        $sql = "SELECT
            (SELECT COUNT(*) FROM USUARIO WHERE estado = TRUE) AS usuariosActivos,
            (SELECT COUNT(*) FROM TICKET WHERE estado <> 'RESUELTO') AS ticketsAbiertos,
            (SELECT COUNT(*) FROM TICKET AS t
                INNER JOIN INCIDENCIA AS i ON i.id = t.id
                WHERE t.estado <> 'RESUELTO') AS incidenciasAbiertas,
            (SELECT COUNT(*) FROM TICKET AS t
                INNER JOIN SERVICIO AS s ON s.idServicio = t.id
                WHERE t.estado <> 'RESUELTO') AS solicitudesAbiertas,
            (SELECT COUNT(*) FROM PRESTAMO WHERE estado = 'ACTIVO') AS prestamosPendientes,
            (SELECT COUNT(*) FROM DISPOSITIVO WHERE estado = TRUE) AS dispositivosActivos,
            (SELECT COUNT(*) FROM REGISTRO_USO) AS registrosUso";

        $consulta = $this->conexion->query($sql);
        $metricas = $consulta->fetch(PDO::FETCH_ASSOC);
        $consulta = null;

        return $metricas === false ? [] : $metricas;
    }
}

