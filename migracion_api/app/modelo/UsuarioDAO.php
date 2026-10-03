<?php

/** Unifica las operaciones de acceso a datos relacionadas con usuarios. */
class UsuarioDAO
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión activa con la base de datos. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /** @return array Usuarios registrados con su rol. */
    public function listarUsuarios(): array
    {
        $consulta = $this->conexion->query(
            "SELECT u.cedula, u.nombre, u.apellido, u.estado,
                CASE WHEN a.cedula IS NOT NULL THEN 1 ELSE 0 END AS administrador,
                CASE WHEN t.cedula IS NOT NULL THEN 1 ELSE 0 END AS tecnico,
                CASE WHEN s.cedula IS NOT NULL THEN 1 ELSE 0 END AS solicitante
            FROM USUARIO AS u
            LEFT JOIN ADMINISTRADOR AS a ON a.cedula = u.cedula
            LEFT JOIN TECNICO AS t ON t.cedula = u.cedula
            LEFT JOIN SOLICITANTE AS s ON s.cedula = u.cedula
            ORDER BY u.cedula"
        );

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Recupera un usuario por su cédula sin exponer la contraseña. */
    public function listarUsuario(string $cedula): ?array
    {
        $consulta = $this->conexion->prepare("SELECT u.cedula, u.nombre, u.apellido, u.estado,
                CASE WHEN a.cedula IS NOT NULL THEN 1 ELSE 0 END AS administrador,
                CASE WHEN t.cedula IS NOT NULL THEN 1 ELSE 0 END AS tecnico,
                CASE WHEN s.cedula IS NOT NULL THEN 1 ELSE 0 END AS solicitante
            FROM USUARIO AS u
            LEFT JOIN ADMINISTRADOR AS a ON a.cedula = u.cedula
            LEFT JOIN TECNICO AS t ON t.cedula = u.cedula
            LEFT JOIN SOLICITANTE AS s ON s.cedula = u.cedula
            WHERE u.cedula = :cedula");
        $consulta->execute(["cedula" => $cedula]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
        return $usuario === false ? null : $usuario;
    }

    /** Registra un usuario y su único rol. */
    public function registrarUsuario(
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
            $consulta = $this->conexion->prepare(
                "INSERT INTO USUARIO (cedula, nombre, apellido, claveHash)
                VALUES (:cedula, :nombre, :apellido, :claveHash)"
            );
            $consulta->execute([
                "cedula" => $cedula,
                "nombre" => $nombre,
                "apellido" => $apellido,
                "claveHash" => $claveHash
            ]);

            $consultaRol = $this->conexion->prepare(
                "INSERT INTO " . $tablasRol[$rol] . " (cedula) VALUES (:cedula)"
            );
            $consultaRol->execute(["cedula" => $cedula]);

            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            return false;
        }
    }

    /** Modifica datos, contraseña y rol exclusivo. */
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
            $consulta = $this->conexion->prepare(
                "UPDATE USUARIO
                SET nombre = :nombre, apellido = :apellido, claveHash = :claveHash
                WHERE cedula = :cedula"
            );
            $consulta->execute([
                "cedula" => $cedula,
                "nombre" => $nombre,
                "apellido" => $apellido,
                "claveHash" => $claveHash
            ]);

            foreach ($tablasRol as $tablaRol) {
                $this->conexion->prepare(
                    "DELETE FROM " . $tablaRol . " WHERE cedula = :cedula"
                )->execute(["cedula" => $cedula]);
            }

            $this->conexion->prepare(
                "INSERT INTO " . $tablasRol[$rol] . " (cedula) VALUES (:cedula)"
            )->execute(["cedula" => $cedula]);

            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            return false;
        }
    }

    /** Activa o desactiva un usuario sin eliminarlo. */
    public function cambiarEstado(string $cedula, bool $estado): bool
    {
        try {
            $consulta = $this->conexion->prepare(
                "UPDATE USUARIO SET estado = :estado WHERE cedula = :cedula"
            );
            $consulta->execute(["cedula" => $cedula, "estado" => $estado ? 1 : 0]);

            return true;
        } catch (PDOException $error) {
            return false;
        }
    }
}
