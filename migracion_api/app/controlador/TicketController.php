<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/TicketDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

/** Atiende solicitudes e incidencias mediante una API de tickets. */
class TicketController
{
    /** Selecciona la operación según el método HTTP. */
    public function gestionar(string $metodo): void
    {
        if (!isset($_SESSION["cedula"])) {
            RespuestaJson::error("Acceso denegado: sesión no iniciada", 401);
        }
        if (!($_SESSION["administrador"] ?? false)
            && !($_SESSION["tecnico"] ?? false)
            && !($_SESSION["solicitante"] ?? false)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            "PUT" => $this->modificar(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    /** Lista todos los tickets o solo los del solicitante. */
    private function listar(): void
    {
        $tipo = $_GET["tipo"] ?? null;
        if ($tipo !== null && !in_array($tipo, ["SOLICITUD", "INCIDENCIA"], true)) {
            RespuestaJson::error("Tipo de ticket incorrecto", 422);
        }
        $cedula = ($_SESSION["solicitante"] ?? false)
            ? $_SESSION["cedula"]
            : null;
        $dao = new TicketDAO($this->conectar());
        RespuestaJson::exito($dao->listarTickets($cedula, $tipo));
    }

    /** Registra una solicitud o incidencia. */
    private function alta(): void
    {
        if (!($_SESSION["solicitante"] ?? false)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }
        $this->verificarCsrf();
        $datos = $this->recibirDatos();
        $tipo = strtoupper(trim($datos["tipo"] ?? ""));

        $comunes = [
            "turno" => trim($datos["turno"] ?? ""),
            "nombreDocente" => trim($datos["nombreDocente"] ?? ""),
            "grupo" => trim($datos["grupo"] ?? ""),
            "asignatura" => trim($datos["asignatura"] ?? ""),
            "idLaboratorio" => trim($datos["idLaboratorio"] ?? ""),
            "numeroDispositivo" => trim($datos["numeroDispositivo"] ?? ""),
            "descripcion" => trim($datos["descripcion"] ?? "")
        ];

        if (!in_array($tipo, ["SOLICITUD", "INCIDENCIA"], true)
            || in_array("", $comunes, true)) {
            RespuestaJson::error("Los datos del ticket no son válidos", 422);
        }

        $dao = new TicketDAO($this->conectar());
        if (!$dao->existenDatosComunes(
            $comunes["turno"],
            $comunes["idLaboratorio"],
            $comunes["numeroDispositivo"]
        )) {
            RespuestaJson::error("El turno o dispositivo no existe", 422);
        }

        $prefijo = $tipo === "SOLICITUD" ? "SOL" : "INC";
        $ticket = $comunes + [
            "id" => $prefijo . strtoupper(substr(uniqid(), -5)),
            "cedulaSolicitante" => $_SESSION["cedula"],
            "fechaApertura" => date("Y-m-d H:i:s")
        ];

        if ($tipo === "SOLICITUD") {
            $ticket["tipoServicio"] = trim($datos["tipoServicio"] ?? "");
            $ticket["fechaEsperada"] = trim($datos["fechaEsperada"] ?? "");
            if ($ticket["tipoServicio"] === "" || $ticket["fechaEsperada"] === ""
                || !$dao->existeTipoServicio($ticket["tipoServicio"])) {
                RespuestaJson::error("Los datos del servicio no son válidos", 422);
            }
            $fechaEsperada = DateTime::createFromFormat("Y-m-d\\TH:i", $ticket["fechaEsperada"]);
            if ($fechaEsperada === false || $fechaEsperada->format("Y-m-d\\TH:i") !== $ticket["fechaEsperada"]) {
                RespuestaJson::error("La fecha esperada no es válida", 422);
            }
            $ticket["fechaEsperada"] = $fechaEsperada->format("Y-m-d H:i:s");
            $resultado = $dao->registrarSolicitud($ticket);
        } else {
            $reportoAlumno = $datos["reportoAlumno"] ?? "";
            $nombreAlumno = trim($datos["nombreAlumno"] ?? "");
            if (!in_array($reportoAlumno, ["SI", "NO"], true)
                || ($reportoAlumno === "SI" && $nombreAlumno === "")) {
                RespuestaJson::error("Los datos de la incidencia no son válidos", 422);
            }
            $ticket["reportoAlumno"] = $reportoAlumno === "SI";
            $ticket["nombreAlumno"] = $reportoAlumno === "SI" ? $nombreAlumno : null;
            $resultado = $dao->registrarIncidencia($ticket);
        }

        if (!$resultado) {
            RespuestaJson::error("No se pudo registrar el ticket", 400);
        }
        RespuestaJson::exito([
            "mensaje" => "Ticket registrado exitosamente",
            "id" => $ticket["id"]
        ], 201);
    }

    /** Gestiona una solicitud o incidencia. */
    private function modificar(): void
    {
        if (!($_SESSION["tecnico"] ?? false)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }
        $this->verificarCsrf();
        $datos = $this->recibirDatos();
        $id = trim($datos["id"] ?? "");
        $tipo = strtoupper(trim($datos["tipo"] ?? ""));
        $estado = trim($datos["estado"] ?? "");
        $dao = new TicketDAO($this->conectar());

        if (!preg_match("/^(SOL|INC)[A-Z0-9]{5}$/", $id)
            || !in_array($tipo, ["SOLICITUD", "INCIDENCIA"], true)
            || !$dao->existeEstado($estado)) {
            RespuestaJson::error("Los datos del ticket no son válidos", 422);
        }

        if ($dao->listarTicket($id, $tipo) === null) {
            RespuestaJson::error("El ticket no existe o no corresponde al tipo indicado", 404);
        }
        if ($tipo === "SOLICITUD") {
            $resultado = $dao->actualizarSolicitud($id, $estado);
        } else {
            $diagnostico = trim($datos["diagnostico"] ?? "");
            $solucion = trim($datos["solucion"] ?? "");
            if ($estado === "RESUELTO" && ($diagnostico === "" || $solucion === "")) {
                RespuestaJson::error("Debe ingresar diagnóstico y solución", 422);
            }
            $resultado = $dao->gestionarIncidencia([
                "id" => $id,
                "estado" => $estado,
                "diagnostico" => $diagnostico,
                "solucion" => $solucion,
                "cedulaTecnico" => $_SESSION["cedula"]
            ]);
        }

        if (!$resultado) {
            RespuestaJson::error("No se pudo gestionar el ticket", 400);
        }
        RespuestaJson::exito(["mensaje" => "Ticket gestionado exitosamente"]);
    }

    /** Lee y comprueba el cuerpo JSON enviado por el formulario. */
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

    /** Compara el token recibido con el token de la sesión. */
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
