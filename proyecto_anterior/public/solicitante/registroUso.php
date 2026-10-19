<?php

require_once __DIR__ . "/../protegerAcceso.php";
verificarRolPublico("solicitante");

require_once __DIR__ . "/../../config/config.php";

$cedulaSolicitante = $_SESSION["cedula"];
$cargarFormularioRegistroUso = true;
$vistaRegistrosUso = RUTA_VISTA . "/solicitante/registroUso.php";

require_once RUTA_CONTROLADOR . "/cargarRegistrosUso.php";
