<?php

/** Gestiona la activación y desactivación lógica de usuarios. */
class ModificarEstadoUsuario
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión PDO activa. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /** @param string $cedula Usuario a actualizar. 
     * @param bool $estado Nuevo estado. 
     * @return bool True si se actualizó. */
    public function cambiarEstado(string $cedula, bool $estado): bool
    {
        try {
            $consulta = $this->conexion->prepare(
                "UPDATE USUARIO SET estado = :estado WHERE cedula = :cedula"
            );

            return $consulta->execute([
                "cedula" => $cedula,
                "estado" => $estado
            ]);
        } catch (PDOException $error) {
            return false;
        }
    }
}
