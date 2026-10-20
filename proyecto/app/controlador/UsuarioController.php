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
        Sesion::verificarRoles(["administrador"]);

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            "PUT" => $this->modificar(),
            "DELETE" => $this->eliminar(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    /** Devuelve todos los usuarios. */
    private function listar(): void
    {
        $dao = new UsuarioDAO(Conexion::conectar());
        if (isset($_GET["cedula"])) {
            $datos = Peticion::recibirConsulta(['cedula']);
            Peticion::validarCedula($datos['cedula']);
            $usuario = $dao->listarUsuario($datos['cedula']);
            if ($usuario === null) {
                RespuestaJson::error("El usuario no existe", 404);
            }
            RespuestaJson::exito($usuario);
            return;
        }
        RespuestaJson::exito($dao->listarUsuarios());
    }

    /** Registra un usuario. */
    private function alta(): void
    {
        Token::verificarCsrf();
        $datos = Peticion::recibirDatos();
        $cedula = trim($datos["cedula"] ?? "");
        $nombre = trim($datos["nombre"] ?? "");
        $apellido = trim($datos["apellido"] ?? "");
        $clave = $datos["clave"] ?? "";
        $confirmarClave = $datos["confirmarClave"] ?? "";
        $rol = trim($datos["rol"] ?? "");

        Peticion::validarCamposObligatorios([$cedula, $nombre, $apellido, $clave, $confirmarClave, $rol]);
        Peticion::validarLongitudes(['nombre' => $nombre, 'apellido' => $apellido], ['nombre' => 50, 'apellido' => 50]);
        Peticion::validarCedula($cedula);
        Peticion::validarClave($clave, $confirmarClave);
        Sesion::validarRol($rol);

        $dao = new UsuarioDAO(Conexion::conectar());
        if ($dao->listarUsuario($cedula) !== null) {
            RespuestaJson::error("La cédula ya está registrada", 409);
        }
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
        Token::verificarCsrf();
        $datos = Peticion::recibirDatos();
        $cedula = trim($datos["cedula"] ?? "");

        Peticion::validarCamposObligatorios([$cedula]);
        Peticion::validarCedula($cedula);

        $dao = new UsuarioDAO(Conexion::conectar());

        if ($dao->listarUsuario($cedula) === null) {
            RespuestaJson::error("El usuario no existe", 404);
        }
        if (array_key_exists("estado", $datos)) {
            $estado = (string) $datos["estado"];
            Peticion::validarCamposObligatorios([$estado]);
            if ($estado !== "1") {
                RespuestaJson::error("Para desactivar un usuario utilice DELETE. PUT permite activar con estado 1", 422);
            }
            $resultado = $dao->cambiarEstado($cedula, true);
        } else {
            $nombre = trim($datos["nombre"] ?? "");
            $apellido = trim($datos["apellido"] ?? "");
            $clave = $datos["clave"] ?? "";
            $confirmarClave = $datos["confirmarClave"] ?? "";
            $rol = trim($datos["rol"] ?? "");

            Peticion::validarCamposObligatorios([$nombre, $apellido, $rol]);
            Peticion::validarLongitudes(['nombre' => $nombre, 'apellido' => $apellido], ['nombre' => 50, 'apellido' => 50]);
            if ($clave !== '' || $confirmarClave !== '') Peticion::validarClave($clave, $confirmarClave);
            Sesion::validarRol($rol);
            $resultado = $dao->modificarUsuario(
                $cedula,
                $nombre,
                $apellido,
                $clave === '' ? '' : password_hash($clave, PASSWORD_DEFAULT),
                $rol
            );
        }

        if (!$resultado) {
            RespuestaJson::error("No se pudo modificar el usuario", 400);
        }
        RespuestaJson::exito(["mensaje" => "Usuario modificado exitosamente"]);
    }

    /** Baja lógica: conserva el usuario, sus roles y todos sus registros asociados. */
    private function eliminar(): void
    {
        Token::verificarCsrf();
        $datos = Peticion::recibirDatos();
        $cedula = trim($datos["cedula"] ?? "");
        Peticion::validarCedula($cedula);
        $dao = new UsuarioDAO(Conexion::conectar());
        if ($dao->listarUsuario($cedula) === null) {
            throw new ErrorHttp("El usuario no existe", 404);
        }
        if (!$dao->cambiarEstado($cedula, false)) {
            throw new ErrorHttp("No se pudo desactivar el usuario", 400);
        }
        RespuestaJson::exito(["mensaje" => "Usuario desactivado exitosamente"]);
    }
}
