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
        Sesion::verificarRoles(["administrador", "tecnico", "solicitante"]);


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
        $dao = new TicketDAO(Conexion::conectar());
        RespuestaJson::exito($dao->listarTickets($cedula, $tipo));
    }

    /** Registra una solicitud o incidencia. */
    private function alta(): void
    {
        Sesion::verificarRoles(["solicitante"]);
        Token::verificarCsrf();
        $datos = Peticion::recibirDatos();
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

        Peticion::validarCamposObligatorios([$tipo, ...array_values($comunes)]);
        Peticion::validarLongitudes($comunes, ['nombreDocente' => 100, 'grupo' => 50, 'asignatura' => 100, 'descripcion' => 500]);
        Peticion::validarLongitudes($datos, ['nombreAlumno' => 100]);

        if (!in_array($tipo, ["SOLICITUD", "INCIDENCIA"], true)) {
            RespuestaJson::error("Los datos del ticket no son válidos", 422);
        }

        $camposDetalle = $tipo === 'SOLICITUD' ? ['tipoServicio', 'fechaEsperada'] : ['reportoAlumno'];
        foreach ($camposDetalle as $campo) Peticion::validarCamposObligatorios([$datos[$campo] ?? '']);
        if ($tipo === 'INCIDENCIA' && ($datos['reportoAlumno'] ?? '') === 'SI') {
            Peticion::validarCamposObligatorios([$datos['nombreAlumno'] ?? '']);
        }

        $dao = new TicketDAO(Conexion::conectar());
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
            $fechaEsperada = Peticion::fecha($ticket["fechaEsperada"]);
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
        Token::verificarCsrf();
        $datos = Peticion::recibirDatos();
        if (($datos['accion'] ?? '') === 'editar') {
            throw new ErrorHttp('No se permite editar los datos de tickets o préstamos', 405);
        }
        Sesion::verificarRoles(["tecnico"]);
        $id = trim($datos["id"] ?? "");
        $tipo = strtoupper(trim($datos["tipo"] ?? ""));
        $estado = strtoupper(trim($datos["estado"] ?? ""));
        Peticion::validarCamposObligatorios([$id, $tipo, $estado]);
        $dao = new TicketDAO(Conexion::conectar());

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
            Peticion::validarLongitudes(['diagnostico' => $diagnostico, 'solucion' => $solucion], ['diagnostico' => 500, 'solucion' => 500]);
            if ($estado === "RESUELTO") {
                Peticion::validarCamposObligatorios([$diagnostico, $solucion]);
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

}
