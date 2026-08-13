<?php

/**
 * Clase de acceso a datos de usuarios.
 */
class AccesoDatosUsuario {
    private PDO $conexion;

    /**
     * Constructor parametrizado que recibe una conexión a la base de datos.
     * @param PDO $conexion La conexion a la base de datos. PRECONDICION: No debe ser NULL.
     */
    public function __construct (PDO $conexion) {
        $this->conexion = $conexion;
    }

    /**
     * Busca un usuario por su cédula y determina el rol.
     * @param string $cedula La cedula del usuario sin puntos ni guiones.
     * @return Usuario|null Los datos del usuario, retorna su objeto si existe, null en caso contrario.
     */
    public function buscarUsuario(string $cedula): ?Usuario
    {
        $sql = "
            SELECT
                u.cedula,
                u.claveHash,
                u.estado AS activo,

                CASE
                    WHEN a.cedula IS NOT NULL THEN 1
                    ELSE 0
                END AS administrador,

                CASE
                    WHEN l.cedula IS NOT NULL THEN 1
                    ELSE 0
                END AS tecnico,

                CASE
                    WHEN s.cedula IS NOT NULL THEN 1
                    ELSE 0
                END AS solicitante

            FROM USUARIO AS u

            LEFT JOIN ADMINISTRADOR AS a
                ON a.cedula = u.cedula

            LEFT JOIN TECNICO AS l
                ON l.cedula = u.cedula

            LEFT JOIN SOLICITANTE AS s
                ON s.cedula = u.cedula

            WHERE u.cedula = :cedula
        ";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute(["cedula" => $cedula]);

        $datos = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($datos === false) {
            return null;
        }

        return new Usuario(
            $datos["cedula"],
            $datos["claveHash"],
            (bool) $datos["activo"],
            (bool) $datos["administrador"],
            (bool) $datos["tecnico"],   
            (bool) $datos["solicitante"]
        );
    }

/**
 * Lista todos los usuarios registrados en la base de datos junto con sus roles.
 * @return array lista de usuarios con sus roles.
 */
public function listarUsuarios (): array {
        $sql = "
            SELECT
    u.cedula,
    u.nombre,
    u.apellido,

    CASE
        WHEN a.cedula IS NOT NULL THEN 1
        ELSE 0
    END AS administrador,

    CASE
        WHEN t.cedula IS NOT NULL THEN 1
        ELSE 0
    END AS tecnico,

    CASE
        WHEN s.cedula IS NOT NULL THEN 1
        ELSE 0
    END AS solicitante

    FROM USUARIO AS u

    LEFT JOIN ADMINISTRADOR AS a
    ON a.cedula = u.cedula

    LEFT JOIN TECNICO AS t
    ON t.cedula = u.cedula

    LEFT JOIN SOLICITANTE AS s
    ON s.cedula = u.cedula

    ORDER BY u.cedula";

        $consulta = $this->conexion->query($sql);

        $usuarios = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $usuarios;
    }

}

?>
