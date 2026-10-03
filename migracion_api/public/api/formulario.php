<?php

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/FormularioController.php";
session_start();
$controlador = new FormularioController();
try {
    $controlador->gestionar($_SERVER["REQUEST_METHOD"]);
} catch (PDOException $error) {
    RespuestaJson::error("No se pudieron cargar las opciones del formulario", 500);
}
