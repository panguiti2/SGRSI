<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/DispositivoDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

/** Atiende las peticiones de la API de dispositivos. */
class DispositivoController
{
    /**
     * Selecciona la operación según el método HTTP recibido.
     * @param string $metodo Método HTTP de la petición.
     * @return void
     */
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
            "PATCH" => $this->modificar(),
            "DELETE" => $this->baja(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    /** @return void */
    private function listar(): void
    {
        $conexion = $this->conectar();
        $dao = new DispositivoDAO($conexion);
        RespuestaJson::exito($dao->listarDispositivos());
    }

    /** @return void */
    private function alta(): void
    {
        $this->verificarCsrf();
        $datos = $this->recibirDatos();

        $idLaboratorio = trim($datos["idLaboratorio"] ?? "");
        $numeroDispositivo = trim($datos["numeroDispositivo"] ?? "");
        $estado = $datos["estado"] ?? "";
        $ultimoCambioEntrada = trim($datos["ultimoCambio"] ?? "");

        if ($idLaboratorio === "" || $numeroDispositivo === ""
            || !in_array($estado, ["0", "1"], true)
            || $ultimoCambioEntrada === "") {
            RespuestaJson::error("Los datos del dispositivo no son válidos", 422);
        }

        $ultimoCambio = DateTime::createFromFormat("Y-m-d\\TH:i", $ultimoCambioEntrada);
        if ($ultimoCambio === false) {
            RespuestaJson::error("La fecha no es válida", 422);
        }

        $conexion = $this->conectar();
        $dao = new DispositivoDAO($conexion);

        if (!$dao->existeLaboratorio($idLaboratorio)) {
            RespuestaJson::error("El laboratorio no existe", 422);
        }

        $resultado = $dao->registrarDispositivo(
            $idLaboratorio,
            $numeroDispositivo,
            $ultimoCambio->format("Y-m-d H:i:s"),
            $estado === "1"
        );

        if (!$resultado) {
            RespuestaJson::error("No se pudo registrar el dispositivo", 400);
        }

        RespuestaJson::exito(["mensaje" => "Dispositivo registrado exitosamente"], 201);
    }

    /** @return void */
    private function modificar(): void
    {
        $this->verificarCsrf();
        $datos = $this->recibirDatos();

        $idLaboratorio = trim($datos["idLaboratorio"] ?? "");
        $numeroDispositivo = trim($datos["numeroDispositivo"] ?? "");
        $modificaciones = trim($datos["modificaciones"] ?? "");
        $estado = $datos["estado"] ?? "";
        $ultimoCambioEntrada = trim($datos["ultimoCambio"] ?? "");
        $ultimoCambio = DateTime::createFromFormat("Y-m-d\\TH:i", $ultimoCambioEntrada);

        if ($idLaboratorio === "" || $numeroDispositivo === ""
            || $modificaciones === "" || $ultimoCambio === false
            || !in_array($estado, ["0", "1"], true)) {
            RespuestaJson::error("Los datos del dispositivo no son válidos", 422);
        }

        $conexion = $this->conectar();
        $dao = new DispositivoDAO($conexion);

        if (!$dao->existeModificacion($modificaciones)) {
            RespuestaJson::error("La modificación no existe", 422);
        }

        $resultado = $dao->modificarDispositivo(
            $idLaboratorio,
            $numeroDispositivo,
            $modificaciones,
            $ultimoCambio->format("Y-m-d H:i:s"),
            $estado === "1"
        );

        if (!$resultado) {
            RespuestaJson::error("No se pudo modificar el dispositivo", 400);
        }

        RespuestaJson::exito(["mensaje" => "Dispositivo modificado exitosamente"]);
    }

    /** @return void */
    private function baja(): void
    {
        $this->verificarCsrf();
        $datos = $this->recibirDatos();

        $idLaboratorio = trim($datos["idLaboratorio"] ?? "");
        $numeroDispositivo = trim($datos["numeroDispositivo"] ?? "");

        if ($idLaboratorio === "" || $numeroDispositivo === "") {
            RespuestaJson::error("Falta identificar el dispositivo", 422);
        }

        $conexion = $this->conectar();
        $dao = new DispositivoDAO($conexion);
        $resultado = $dao->eliminarDispositivo($idLaboratorio, $numeroDispositivo);

        if (!$resultado) {
            RespuestaJson::error("No se pudo eliminar el dispositivo", 400);
        }

        RespuestaJson::exito(["mensaje" => "Dispositivo eliminado exitosamente"]);
    }

    /** @return array Datos JSON enviados por el cliente. */
    private function recibirDatos(): array
    {
        return json_decode(file_get_contents("php://input"), true) ?? [];
    }

    /** @return void */
    private function verificarCsrf(): void
    {
        $token = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? "";
        if (!isset($_SESSION["csrfToken"])
            || !hash_equals($_SESSION["csrfToken"], $token)) {
            RespuestaJson::error("Solicitud rechazada", 403);
        }
    }

    /** @return PDO Conexión activa. */
    private function conectar(): PDO
    {
        $conector = new ConectorPDO(
            $_ENV["DB_HOST"],
            $_ENV["DB_USUARIO"],
            $_ENV["DB_CLAVE"],
            $_ENV["DB_NOMBRE"]
        );

        return $conector->establecerConexion();
    }
}

