<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/FormularioDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

/** Atiende la carga de opciones y el token de las páginas HTML. */
class FormularioController
{
    /** Comprueba el método, la sesión y el rol antes de devolver las opciones. */
    public function gestionar(string $metodo): void
    {
        Sesion::verificarRoles(['administrador', 'tecnico', 'solicitante']);

        if ($metodo !== "GET") {
            RespuestaJson::error("Método no permitido", 405);
        }
        $consulta = Peticion::recibirConsulta(['rol', 'seccion']);
        $rol = $consulta['rol'];
        $seccion = $consulta['seccion'];
        $paginas = [
            "administrador" => ["inicio", "usuarios", "inventario", "incidencias", "metricas"],
            "tecnico" => ["inicio", "inventario", "incidencias", "solicitudes", "prestamos", "registrosUso", "metricas"],
            "solicitante" => ["inicio", "incidencias", "solicitudes", "registroUso"]
        ];
        if (!is_string($rol) || !is_string($seccion)
            || !isset($paginas[$rol]) || !($_SESSION[$rol] ?? false)
            || !in_array($seccion, $paginas[$rol], true)) {
            RespuestaJson::error("No tiene autorización para acceder a este panel", 403);
        }
        $datos = ["csrfToken" => Token::generarTokenCSRF()];
        $tieneFormulario = ($rol === "solicitante" && $seccion !== "inicio")
            || ($rol === "administrador" && $seccion === "inventario")
            || ($rol === "tecnico" && in_array($seccion, ["incidencias", "solicitudes", "prestamos"], true));
        if ($tieneFormulario) {
            $dao = new FormularioDAO(Conexion::conectar());
            if ($rol === "solicitante" || $seccion === "inventario") {
                $datos["laboratorios"] = $dao->listarLaboratorios();
            }
            if ($rol === "solicitante" || $seccion === "prestamos") {
                $datos["turnos"] = $dao->listarTurnos();
            }
            if ($rol === "solicitante" && $seccion !== "registroUso") {
                $datos["dispositivosFormulario"] = $dao->listarDispositivos();
            }
            if ($rol === "solicitante" && $seccion === "solicitudes") {
                $datos["tiposServicio"] = $dao->listarTiposServicio();
            }
            if ($rol === "administrador" && $seccion === "inventario") {
                $datos["modificaciones"] = $dao->listarModificaciones();
            }
            if ($rol === "tecnico" && $seccion !== "prestamos") {
                $datos["estadosTicket"] = $dao->listarEstados();
            }
        }
        RespuestaJson::exito($datos);
    }
}
