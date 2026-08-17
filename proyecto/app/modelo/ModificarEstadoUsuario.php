<?php

class ModificarEstadoUsuario
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

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
