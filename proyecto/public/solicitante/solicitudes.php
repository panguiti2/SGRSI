<?php
require_once __DIR__ . "/../../config/config.php";
require_once RUTA_CONTROLADOR . "/verificarAcceso.php";
verificarAcceso("solicitante");
$cedulaSolicitante = $_SESSION["cedula"];
$vistaSolicitudes = RUTA_VISTA . "/solicitante/solicitudes.php";
require_once RUTA_CONTROLADOR . "/cargarSolicitudes.php";
