<?php

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/PrestamoController.php";

session_start();

$controlador = new PrestamoController();
try {
    $controlador->gestionar($_SERVER["REQUEST_METHOD"]);
} catch (PDOException $error) {
    RespuestaJson::error("No se pudo completar la operación con la base de datos", 500);
}
