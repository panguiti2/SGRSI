<?php
class AltaDatosRegistroUso
{
    private PDO $conexion;
    public function __construct(PDO $conexion) { $this->conexion = $conexion; }
    public function registrar(array $registro): bool
    {
        try {
            $this->conexion->beginTransaction();
            $sql = "INSERT INTO REGISTRO_USO (idRegistro, cedulaSolicitante, laboratorio, turno, fechaHora, docente, grupo, asignatura, usoMaquinas, huboIncidencias)
                    VALUES (:idRegistro, :cedulaSolicitante, :laboratorio, :turno, :fechaHora, :docente, :grupo, :asignatura, :usoMaquinas, :huboIncidencias)";
            $this->conexion->prepare($sql)->execute($registro);
            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) $this->conexion->rollBack();
            return false;
        }
    }
}
