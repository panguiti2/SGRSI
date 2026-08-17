<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/BajaDatosDispositivo.php";

$idLaboratorio = trim($_POST["idLab"] ?? "");
$numeroDispositivo = trim($_POST["numeroDispositivo"] ?? "");

if ($idLaboratorio === "" || $numeroDispositivo === "") {
    header("Location: inventario.php?error=datos_incorrectos");
    exit;
}

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$resultado = (new BajaDatosDispositivo($conexion))->eliminarDispositivo($idLaboratorio, $numeroDispositivo);
$conectorPDO->desconectar();

header("Location: inventario.php?" . ($resultado ? "exito=dispositivo_eliminado" : "error=dispositivo_en_uso"));
exit;
