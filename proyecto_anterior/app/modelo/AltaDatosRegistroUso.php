<?php

/**
 * Registra usos de laboratorios en la base de datos.
 */
class AltaDatosRegistroUso
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión PDO activa. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Inserta un registro de uso validado.
     * @param array $registro Datos del registro a guardar.
     * @return bool True si el registro se insertó correctamente.
     */
    public function registrar(array $registro): bool
    {
        try {
            $this->conexion->beginTransaction();
            $sql = "
                INSERT INTO REGISTRO_USO (
                    idRegistro, cedulaSolicitante, idLaboratorio, turno,
                    fechaHora, nombreDocente, grupo, asignatura,
                    usoMaquinas, huboIncidencias
                ) VALUES (
                    :idRegistro, :cedulaSolicitante, :idLaboratorio, :turno,
                    :fechaHora, :nombreDocente, :grupo, :asignatura,
                    :usoMaquinas, :huboIncidencias
                )
            ";

            $consulta = $this->conexion->prepare($sql);
            $consulta->execute($registro);

            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }
}
