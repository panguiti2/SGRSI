<?php

class AccesoDatosSolicitud
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

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
