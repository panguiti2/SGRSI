<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/verificarAcceso.php";
verificarAcceso("solicitante");
$cedulaSolicitante = $_SESSION["cedula"];
$vistaRegistrosUso = RUTA_VISTA . "/solicitante/registroUso.php";
require_once RUTA_CONTROLADOR . "/cargarRegistrosUso.php";
