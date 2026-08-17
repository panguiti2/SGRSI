<?php

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/ModificarDatosDispositivo.php";
require_once RUTA_MODELO . "/AccesoDatosCatalogo.php";

$idLaboratorio = trim($_POST["idLabFijo"] ?? $_POST["idLab"] ?? "");
$numeroDispositivo = trim($_POST["numeroDispositivo"] ?? "");
$modificaciones = trim($_POST["modificaciones"] ?? "");
$estado = $_POST["estado"] ?? "";
$ultimoCambioEntrada = trim($_POST["ultimoCambio"] ?? "");
$ultimoCambio = DateTime::createFromFormat("Y-m-d\\TH:i", $ultimoCambioEntrada);

if (
    $idLaboratorio === "" || $numeroDispositivo === "" || $ultimoCambio === false
    || !in_array($estado, ["0", "1"], true)
) {
    header("Location: inventario.php?error=datos_incorrectos");
    exit;
}

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$accesoDatosCatalogo = new AccesoDatosCatalogo($conexion);

if (!$accesoDatosCatalogo->existeModificacionDispositivo($modificaciones)) {
    $conectorPDO->desconectar();
    header("Location: inventario.php?error=datos_incorrectos");
    exit;
}

$resultado = (new ModificarDatosDispositivo($conexion))->modificarDispositivo(
    $idLaboratorio,
    $numeroDispositivo,
    $modificaciones,
    $ultimoCambio->format("Y-m-d H:i:s"),
    $estado === "1"
);
$conectorPDO->desconectar();

header("Location: inventario.php?" . ($resultado ? "exito=dispositivo_modificado" : "error=error_dispositivo"));
exit;
