<?php

class AccesoDatosIncidencia
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

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
