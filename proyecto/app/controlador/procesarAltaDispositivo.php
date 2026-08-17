<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/AltaDatosDispositivo.php";
require_once RUTA_MODELO . "/AccesoDatosDispositivo.php";

$idLab = trim($_POST["idLab"] ?? "");
$numeroDispositivo = trim($_POST["numeroDispositivo"] ?? "");
$estado = trim($_POST["estado"] ?? "");
$ultimoCambio = trim($_POST["ultimoCambio"] ?? "");


if ($idLab === "" || $numeroDispositivo === "" || $estado === "" || $ultimoCambio === "" ) {
    header("Location: inventario.php?error=campos_vacios");
    exit;
}

if (!in_array($estado, ["1", "0"], true)) {
    header("Location: inventario.php?error=datos_incorrectos");
    exit;
}

$fecha = DateTime::createFromFormat("Y-m-d\\TH:i", $ultimoCambio);

if ($fecha === false) {
    header("Location: inventario.php?error=datos_incorrectos");
    exit;
}


$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);

$conexion = $conectorPDO->establecerConexion();

if ($conexion === null) {
    header("Location: inventario.php?error=conexion");
    exit;
}

$accesoDatosDispositivo = new AccesoDatosDispositivo($conexion);

if (!$accesoDatosDispositivo->existeLaboratorio($idLab)) {
    $conectorPDO->desconectar();
    header("Location: inventario.php?error=datos_incorrectos");
    exit;
}

$altaDatosDispositivo = new AltaDatosDispositivo($conexion);

$resultado = $altaDatosDispositivo->registrarDispositivo(
    $idLab,
    $numeroDispositivo,
    $fecha->format("Y-m-d H:i:s"),
    $estado === "1"
);

$conectorPDO->desconectar();


if (!$resultado) {
    header("Location: inventario.php?error=error_dispositivo");
    exit;
}

header("Location: inventario.php?exito=dispositivo");
exit;

?>
