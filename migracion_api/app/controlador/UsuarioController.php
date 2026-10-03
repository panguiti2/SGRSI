<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/UsuarioDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

/** Atiende las peticiones de la API de usuarios. */
class UsuarioController
{
    /** Selecciona la operación según el método HTTP. */
    public function gestionar(string $metodo): void
    {
        if (!isset($_SESSION["cedula"])) {
            RespuestaJson::error("Acceso denegado: sesión no iniciada", 401);
        }
        if (!($_SESSION["administrador"] ?? false)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            "PUT" => $this->modificar(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    /** Devuelve todos los usuarios. */
    private function listar(): void
    {
        $dao = new UsuarioDAO($this->conectar());
        if (isset($_GET["cedula"])) {
            if (!is_string($_GET["cedula"]) || !preg_match("/^[1-9][0-9]{7}$/", $_GET["cedula"])) {
                RespuestaJson::error("La cédula no es válida", 422);
            }
            $usuario = $dao->listarUsuario($_GET["cedula"]);
            if ($usuario === null) {
                RespuestaJson::error("El usuario no existe", 404);
            }
            RespuestaJson::exito($usuario);
        }
        RespuestaJson::exito($dao->listarUsuarios());
    }

    /** Registra un usuario. */
    private function alta(): void
    {
        $this->verificarCsrf();
        $datos = $this->recibirDatos();
        $cedula = trim($datos["cedula"] ?? "");
        $nombre = trim($datos["nombre"] ?? "");
        $apellido = trim($datos["apellido"] ?? "");
        $clave = $datos["clave"] ?? "";
        $confirmarClave = $datos["confirmarClave"] ?? "";
        $rol = trim($datos["rol"] ?? "");

        if ($cedula === "" || $nombre === "" || $apellido === ""
            || $clave === "" || $confirmarClave === "" || $rol === "") {
            RespuestaJson::error("Existen campos vacíos", 422);
        }
        if (!preg_match("/^[1-9][0-9]{7}$/", $cedula)
            || !in_array($rol, ["administrador", "tecnico", "solicitante"], true)
            || strlen($clave) < 12 || $clave !== $confirmarClave) {
            RespuestaJson::error("Los datos del usuario no son válidos", 422);
        }

        $dao = new UsuarioDAO($this->conectar());
        $resultado = $dao->registrarUsuario(
            $cedula,
            $nombre,
            $apellido,
            password_hash($clave, PASSWORD_DEFAULT),
            $rol
        );

        if (!$resultado) {
            RespuestaJson::error("No se pudo registrar el usuario", 400);
        }
        RespuestaJson::exito(["mensaje" => "Usuario registrado exitosamente"], 201);
    }

    /** Modifica los datos o el estado de un usuario. */
    private function modificar(): void
    {
        $this->verificarCsrf();
        $datos = $this->recibirDatos();
        $cedula = trim($datos["cedula"] ?? "");

        if (!preg_match("/^[1-9][0-9]{7}$/", $cedula)) {
            RespuestaJson::error("La cédula no es válida", 422);
        }

        $dao = new UsuarioDAO($this->conectar());

        if ($dao->listarUsuario($cedula) === null) {
            RespuestaJson::error("El usuario no existe", 404);
        }
        if (array_key_exists("estado", $datos)) {
            $estado = (string) $datos["estado"];
            if (!in_array($estado, ["0", "1"], true)) {
                RespuestaJson::error("El estado no es válido", 422);
            }
            $resultado = $dao->cambiarEstado($cedula, $estado === "1");
        } else {
            $nombre = trim($datos["nombre"] ?? "");
            $apellido = trim($datos["apellido"] ?? "");
            $clave = $datos["clave"] ?? "";
            $confirmarClave = $datos["confirmarClave"] ?? "";
            $rol = trim($datos["rol"] ?? "");

            if ($nombre === "" || $apellido === "" || strlen($clave) < 12 || $clave !== $confirmarClave
                || !in_array($rol, ["administrador", "tecnico", "solicitante"], true)) {
                RespuestaJson::error("Los datos del usuario no son válidos", 422);
            }
            $resultado = $dao->modificarUsuario(
                $cedula,
                $nombre,
                $apellido,
                password_hash($clave, PASSWORD_DEFAULT),
                $rol
            );
        }

        if (!$resultado) {
            RespuestaJson::error("No se pudo modificar el usuario", 400);
        }
        RespuestaJson::exito(["mensaje" => "Usuario modificado exitosamente"]);
    }

    /** Lee el cuerpo JSON de la petición. */
    private function recibirDatos(): array
    {
        $datos = json_decode(file_get_contents("php://input"), true);
        if (!is_array($datos)) {
            RespuestaJson::error("Los datos enviados no son válidos", 422);
        }
        foreach ($datos as $valor) {
            if (!is_string($valor)) {
                RespuestaJson::error("Los datos enviados no son válidos", 422);
            }
        }
        return $datos;
    }

    /** Comprueba el token enviado en la cabecera. */
    private function verificarCsrf(): void
    {
        $token = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? "";
        if (!isset($_SESSION["csrfToken"])
            || !hash_equals($_SESSION["csrfToken"], $token)) {
            RespuestaJson::error("Solicitud rechazada", 403);
        }
    }

    /** Crea y devuelve la conexión PDO. */
    private function conectar(): PDO
    {
        return (new ConectorPDO(
            $_ENV["DB_HOST"], $_ENV["DB_USUARIO"],
            $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]
        ))->establecerConexion();
    }
}
