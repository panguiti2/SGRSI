<?php

require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/DispositivoController.php";

session_start();

$controlador = new DispositivoController();
$controlador->gestionar($_SERVER["REQUEST_METHOD"]);
