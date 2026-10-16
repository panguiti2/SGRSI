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
        Sesion::verificarRoles(["tecnico", "solicitante"]);


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
        $dao = new RegistroUsoDAO(Conexion::conectar());
        RespuestaJson::exito($dao->listarRegistros($cedula));
    }

    /** Registra un uso de laboratorio. */
    private function alta(): void
    {
        Sesion::verificarRoles(["solicitante"]);
        Token::verificarCsrf();
        $datos = Peticion::recibirDatos();

        $laboratorio = trim($datos["idLaboratorio"] ?? "");
        $turno = trim($datos["turno"] ?? "");
        $fechaEntrada = trim($datos["fechaHora"] ?? "");
        $docente = trim($datos["nombreDocente"] ?? "");
        $grupo = trim($datos["grupo"] ?? "");
        $asignatura = trim($datos["asignatura"] ?? "");
        $usoMaquinas = $datos["usoMaquinas"] ?? "";
        $huboIncidencias = $datos["huboIncidencias"] ?? "";

        Peticion::validarCamposObligatorios([$laboratorio, $turno, $fechaEntrada, $docente, $grupo, $asignatura, $usoMaquinas, $huboIncidencias]);
        Peticion::validarLongitudes(['nombreDocente' => $docente, 'grupo' => $grupo, 'asignatura' => $asignatura], ['nombreDocente' => 100, 'grupo' => 50, 'asignatura' => 100]);
        $fechaHora = Peticion::fecha($fechaEntrada);
        if (!in_array($usoMaquinas, ["SI", "NO"], true)
            || !in_array($huboIncidencias, ["SI", "NO"], true)) {
            RespuestaJson::error("Los datos del registro no son válidos", 422);
        }

        $dao = new RegistroUsoDAO(Conexion::conectar());
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

}
