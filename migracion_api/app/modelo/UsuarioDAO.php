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

    /** Recupera el hash exclusivamente para verificar el acceso en el servidor. */
    public function buscarCredenciales(string $cedula): ?array
    {
        $usuario = $this->listarUsuario($cedula);
        if ($usuario === null) return null;
        $consulta = $this->conexion->prepare('SELECT claveHash FROM USUARIO WHERE cedula = :cedula');
        $consulta->execute(['cedula' => $cedula]);
        $usuario['claveHash'] = $consulta->fetchColumn();
        return $usuario;
    }

    /** Registra un usuario y su único rol. */
    public function registrarUsuario(
        string $cedula,
        string $nombre,
        string $apellido,
        string $claveHash,
        string $rol
    ): bool {
        $tablasRol = Sesion::TABLAS_ROL;

        if (!isset($tablasRol[$rol])) {
            throw new ErrorHttp('El rol no es válido');
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
            ErrorSql::traducir($error, 'La cédula ya está registrada');
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
        $tablasRol = Sesion::TABLAS_ROL;

        if (!isset($tablasRol[$rol])) {
            throw new ErrorHttp('El rol no es válido');
        }

        try {
            $this->conexion->beginTransaction();
            $bloqueo = $this->conexion->prepare('SELECT cedula FROM USUARIO WHERE cedula = :cedula FOR UPDATE');
            $bloqueo->execute(['cedula' => $cedula]);
            if ($bloqueo->fetchColumn() === false) throw new ErrorHttp('El usuario no existe', 404);
            $usuario = $this->listarUsuario($cedula);
            $roles = array_values(array_filter(array_keys($tablasRol), fn($actual) => (bool) $usuario[$actual]));
            if ($roles !== [$rol]) {
                foreach ($tablasRol as $actual => $tablaRol) {
                    if ($usuario[$actual] && $actual !== $rol) {
                        $this->conexion->prepare('DELETE FROM ' . $tablaRol . ' WHERE cedula = :cedula')
                            ->execute(['cedula' => $cedula]);
                    }
                }
                if (!$usuario[$rol]) {
                    $this->conexion->prepare('INSERT INTO ' . $tablasRol[$rol] . ' (cedula) VALUES (:cedula)')
                        ->execute(['cedula' => $cedula]);
                }
            }
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

            return $this->conexion->commit();
        } catch (Throwable $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            if ($error instanceof PDOException) ErrorSql::traducir($error, 'La cédula ya está registrada');
            throw $error;
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
            throw $error;
        }
    }
}
