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
        if (!isset($_SESSION["cedula"])) {
            RespuestaJson::error("Acceso denegado: sesión no iniciada", 401);
        }
        if (!($_SESSION["administrador"] ?? false)
            && !($_SESSION["tecnico"] ?? false)) {
            RespuestaJson::error("Acceso denegado: rol incorrecto", 403);
        }
        if ($metodo !== "GET") {
            RespuestaJson::error("Método no permitido", 405);
        }

        $conector = new ConectorPDO(
            $_ENV["DB_HOST"], $_ENV["DB_USUARIO"],
            $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]
        );
        $dao = new MetricasDAO($conector->establecerConexion());
        RespuestaJson::exito($dao->obtenerMetricas());
    }
}

