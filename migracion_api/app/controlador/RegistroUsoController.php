<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RegistroUsoDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

/** Atiende las peticiones de la API de registros de uso. */
class RegistroUsoController
{
    /** Selecciona la operación según el método HTTP. */
    public function gestionar(string $metodo): void
    {
        if (!isset($_SESSION["cedula"])) {
            RespuestaJson::error("Acceso denegado: sesión no iniciada", 401);
        }
        if (!($_SESSION["solicitante"] ?? false)
            && !($_SESSION["tecnico"] ?? false)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    /** Devuelve registros según el rol. */
    private function listar(): void
    {
        $cedula = ($_SESSION["solicitante"] ?? false)
            ? $_SESSION["cedula"]
            : null;
        $dao = new RegistroUsoDAO($this->conectar());
        RespuestaJson::exito($dao->listarRegistros($cedula));
    }

    /** Registra un uso de laboratorio. */
    private function alta(): void
    {
        if (!($_SESSION["solicitante"] ?? false)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }
        $this->verificarCsrf();
        $datos = $this->recibirDatos();

        $laboratorio = trim($datos["idLaboratorio"] ?? "");
        $turno = trim($datos["turno"] ?? "");
        $fechaEntrada = trim($datos["fechaHora"] ?? "");
        $fechaHora = DateTime::createFromFormat("Y-m-d\\TH:i", $fechaEntrada);
        if ($fechaHora === false || $fechaHora->format("Y-m-d\\TH:i") !== $fechaEntrada) {
            RespuestaJson::error("La fecha del registro no es válida", 422);
        }
        $docente = trim($datos["nombreDocente"] ?? "");
        $grupo = trim($datos["grupo"] ?? "");
        $asignatura = trim($datos["asignatura"] ?? "");
        $usoMaquinas = $datos["usoMaquinas"] ?? "";
        $huboIncidencias = $datos["huboIncidencias"] ?? "";

        if (in_array("", [$laboratorio, $turno, $docente, $grupo, $asignatura], true)
            || !in_array($usoMaquinas, ["SI", "NO"], true)
            || !in_array($huboIncidencias, ["SI", "NO"], true)) {
            RespuestaJson::error("Los datos del registro no son válidos", 422);
        }

        $dao = new RegistroUsoDAO($this->conectar());
        if (!$dao->existenOpciones($laboratorio, $turno)) {
            RespuestaJson::error("El laboratorio o turno no existe", 422);
        }

        $resultado = $dao->registrar([
            "idRegistro" => "REG" . strtoupper(substr(uniqid(), -5)),
            "cedulaSolicitante" => $_SESSION["cedula"],
            "idLaboratorio" => $laboratorio,
            "turno" => $turno,
            "fechaHora" => $fechaHora->format("Y-m-d H:i:s"),
            "nombreDocente" => $docente,
            "grupo" => $grupo,
            "asignatura" => $asignatura,
            "usoMaquinas" => $usoMaquinas === "SI",
            "huboIncidencias" => $huboIncidencias === "SI"
        ]);

        if (!$resultado) {
            RespuestaJson::error("No se pudo registrar el uso", 400);
        }
        RespuestaJson::exito(["mensaje" => "Uso registrado exitosamente"], 201);
    }

    /** Lee y comprueba los datos JSON recibidos. */
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

    /** Comprueba el token de la sesión. */
    private function verificarCsrf(): void
    {
        $token = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? "";
        if (!isset($_SESSION["csrfToken"])
            || !hash_equals($_SESSION["csrfToken"], $token)) {
            RespuestaJson::error("Solicitud rechazada", 403);
        }
    }

    /** Establece la conexión utilizando la configuración del sistema. */
    private function conectar(): PDO
    {
        return (new ConectorPDO(
            $_ENV["DB_HOST"], $_ENV["DB_USUARIO"],
            $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]
        ))->establecerConexion();
    }
}
