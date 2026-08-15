<?php
class AccesoDatosRegistroUso
{
    private PDO $conexion;
    public function __construct(PDO $conexion) { $this->conexion = $conexion; }
    public function listarRegistros(?string $cedulaSolicitante = null): array
    {
        $sql = "SELECT idRegistro, cedulaSolicitante, laboratorio, turno, fechaHora, docente, grupo, asignatura, usoMaquinas, huboIncidencias FROM REGISTRO_USO";
        if ($cedulaSolicitante !== null) $sql .= " WHERE cedulaSolicitante = :cedulaSolicitante";
        $sql .= " ORDER BY fechaHora DESC";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($cedulaSolicitante === null ? [] : ["cedulaSolicitante" => $cedulaSolicitante]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
