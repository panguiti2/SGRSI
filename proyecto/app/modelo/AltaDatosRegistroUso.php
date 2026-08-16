<?php
/**
 * Registra el uso de laboratorios en la base de datos.
 */
class AltaDatosRegistroUso
{
    private PDO $conexion;
    /**
     * Inicializa el alta de registros de uso con una conexión activa.
     * @param PDO $conexion Conexión activa con la base de datos.
     */
    public function __construct(PDO $conexion) { $this->conexion = $conexion; }

    /**
     * Guarda un registro de uso dentro de una transacción.
     * @param array $registro Datos del registro de uso.
     * @return bool Verdadero si el registro fue guardado.
     */
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
