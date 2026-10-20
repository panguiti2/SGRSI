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
        Sesion::verificarRoles($metodo === 'GET' ? ['administrador', 'tecnico'] : ['administrador']);

        match ($metodo) {
            "GET" => $this->listar(),
            "POST" => $this->alta(),
            "PUT" => $this->modificar(),
            "DELETE" => $this->baja(),
            default => RespuestaJson::error("Método no permitido", 405),
        };
    }

    /** @return void */
    private function listar(): void
    {
        $conexion = Conexion::conectar();
        $dao = new DispositivoDAO($conexion, $_SESSION['cedula']);
        if (isset($_GET['retirados'])) {
            $consulta = Peticion::recibirConsulta(['retirados']);
            if (!in_array($consulta['retirados'], ['0','1'], true)) throw new ErrorHttp('El filtro de inventario no es válido', 422);
            if ($consulta['retirados'] === '1') Sesion::verificarRoles(['administrador']);
            RespuestaJson::exito($dao->listarDispositivos($consulta['retirados'] === '1'));
            return;
        }
        if (isset($_GET["idLaboratorio"]) || isset($_GET["numeroDispositivo"])) {
            $datos = Peticion::recibirConsulta(['idLaboratorio', 'numeroDispositivo']);
            $idLaboratorio = $datos['idLaboratorio'];
            $numeroDispositivo = $datos['numeroDispositivo'];
            Peticion::validarLongitudes($datos, ['idLaboratorio' => 8, 'numeroDispositivo' => 8]);
            $dispositivo = $dao->listarDispositivo($idLaboratorio, $numeroDispositivo);
            if ($dispositivo === null) {
                RespuestaJson::error("El dispositivo no existe", 404);
            }
            RespuestaJson::exito($dispositivo);
            return;
        }
        RespuestaJson::exito($dao->listarDispositivos());
    }

    /** @return void */
    private function alta(): void
    {
        Token::verificarCsrf();
        $datos = Peticion::recibirDatos();

        $idLaboratorio = trim($datos["idLaboratorio"] ?? "");
        $numeroDispositivo = trim($datos["numeroDispositivo"] ?? "");
        $estado = $datos["estado"] ?? "";
        $ultimoCambioEntrada = trim($datos["ultimoCambio"] ?? "");

        Peticion::validarCamposObligatorios([$idLaboratorio, $numeroDispositivo, $estado, $ultimoCambioEntrada]);
        Peticion::validarLongitudes(['idLaboratorio' => $idLaboratorio, 'numeroDispositivo' => $numeroDispositivo], ['idLaboratorio' => 8, 'numeroDispositivo' => 8]);
        if ($idLaboratorio === "" || $numeroDispositivo === ""
            || !in_array($estado, ["0", "1"], true)
            || $ultimoCambioEntrada === "") {
            RespuestaJson::error("Los datos del dispositivo no son válidos", 422);
        }

        $ultimoCambio = Peticion::fecha($ultimoCambioEntrada);

        $conexion = Conexion::conectar();
        $dao = new DispositivoDAO($conexion, $_SESSION['cedula']);

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
        Token::verificarCsrf();
        $datos = Peticion::recibirDatos();
        if (($datos['accion'] ?? '') === 'restaurar') {
            $laboratorio = trim($datos['idLaboratorio'] ?? '');
            $numero = trim($datos['numeroDispositivo'] ?? '');
            Peticion::validarCamposObligatorios([$laboratorio, $numero]);
            Peticion::validarLongitudes(['laboratorio'=>$laboratorio, 'numero'=>$numero], ['laboratorio'=>8, 'numero'=>8]);
            $dao = new DispositivoDAO(Conexion::conectar(), $_SESSION['cedula']);
            $dao->restaurarDispositivo($laboratorio, $numero);
            RespuestaJson::exito(['mensaje'=>'Dispositivo restaurado. Revíselo y actívelo desde Modificar para permitir nuevos tickets']);
            return;
        }

        $idLaboratorio = trim($datos["idLaboratorio"] ?? "");
        $numeroDispositivo = trim($datos["numeroDispositivo"] ?? "");
        $modificaciones = trim($datos["modificaciones"] ?? "");
        $estado = $datos["estado"] ?? "";
        $ultimoCambioEntrada = trim($datos["ultimoCambio"] ?? "");
        Peticion::validarCamposObligatorios([$idLaboratorio, $numeroDispositivo, $modificaciones, $estado, $ultimoCambioEntrada]);
        Peticion::validarLongitudes($datos, ['idLaboratorio' => 8, 'numeroDispositivo' => 8, 'modificaciones' => 255]);
        $ultimoCambio = Peticion::fecha($ultimoCambioEntrada);

        if ($idLaboratorio === "" || $numeroDispositivo === ""
            || $modificaciones === ""
            || !in_array($estado, ["0", "1"], true)) {
            RespuestaJson::error("Los datos del dispositivo no son válidos", 422);
        }

        $conexion = Conexion::conectar();
        $dao = new DispositivoDAO($conexion, $_SESSION['cedula']);

        if (!$dao->existeModificacion($modificaciones)) {
            RespuestaJson::error("La modificación no existe", 422);
        }
        if ($dao->listarDispositivo($idLaboratorio, $numeroDispositivo) === null) {
            RespuestaJson::error("El dispositivo no existe", 404);
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
        Token::verificarCsrf();
        $datos = Peticion::recibirDatos();

        $idLaboratorio = trim($datos["idLaboratorio"] ?? "");
        $numeroDispositivo = trim($datos["numeroDispositivo"] ?? "");

        Peticion::validarCamposObligatorios([$idLaboratorio, $numeroDispositivo]);
        Peticion::validarLongitudes($datos, ['idLaboratorio' => 8, 'numeroDispositivo' => 8]);

        $conexion = Conexion::conectar();
        $dao = new DispositivoDAO($conexion, $_SESSION['cedula']);
        $resultado = $dao->eliminarDispositivo($idLaboratorio, $numeroDispositivo);

        if (!$resultado) {
            RespuestaJson::error("No se pudo dar de baja el dispositivo. Puede estar asociado a un ticket.", 400);
        }

        RespuestaJson::exito(["mensaje" => "Dispositivo dado de baja correctamente"]);
    }

}
