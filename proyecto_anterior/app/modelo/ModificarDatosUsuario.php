<?php

/** Actualiza los datos personales, contraseña y rol exclusivo de un usuario. */
class ModificarDatosUsuario
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión PDO activa. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /**
     * Modifica un usuario y reemplaza su rol anterior por el rol recibido.
     * @param string $cedula Cédula del usuario.
     * @param string $nombre Nombre actualizado.
     * @param string $apellido Apellido actualizado.
     * @param string $claveHash Contraseña ya hasheada.
     * @param string $rol Rol exclusivo seleccionado.
     * @return bool True si se confirma toda la operación.
     */
    public function modificarUsuario(
        string $cedula,
        string $nombre,
        string $apellido,
        string $claveHash,
        string $rol
    ): bool {
        $tablasRol = [
            "administrador" => "ADMINISTRADOR",
            "tecnico" => "TECNICO",
            "solicitante" => "SOLICITANTE"
        ];

        if (!isset($tablasRol[$rol])) {
            return false;
        }

        try {
            $this->conexion->beginTransaction();

            $sqlUsuario = "UPDATE USUARIO
                SET nombre = :nombre, apellido = :apellido, claveHash = :claveHash
                WHERE cedula = :cedula";
            $parametros = [
                "cedula" => $cedula,
                "nombre" => $nombre,
                "apellido" => $apellido,
                "claveHash" => $claveHash
            ];
            $this->conexion->prepare($sqlUsuario)->execute($parametros);

            foreach ($tablasRol as $tablaRol) {
                $this->conexion->prepare("DELETE FROM " . $tablaRol . " WHERE cedula = :cedula")
                    ->execute(["cedula" => $cedula]);
            }

            $this->conexion->prepare("INSERT INTO " . $tablasRol[$rol] . " (cedula) VALUES (:cedula)")
                ->execute(["cedula" => $cedula]);

            $this->conexion->commit();
            return true;
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return false;
        }
    }
}
