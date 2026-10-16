<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/MetricasDAO.php";
require_once RUTA_VISTA . "/RespuestaJson.php";

/** Atiende la consulta de indicadores mediante la API. */
class MetricasController
{
    /** Selecciona la operación según el método HTTP. */
    public function gestionar(string $metodo): void
    {
        Sesion::verificarRoles(["administrador", "tecnico"]);

        if ($metodo !== "GET") {
            RespuestaJson::error("Método no permitido", 405);
        }

        $dao = new MetricasDAO(Conexion::conectar());
        RespuestaJson::exito($dao->obtenerMetricas());
    }
}
