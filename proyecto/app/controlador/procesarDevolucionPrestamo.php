<?php

/** Controlador que cierra un préstamo al registrar su devolución. */

require_once RUTA_MODELO . "/ConectorPDO.php";
require_once RUTA_MODELO . "/RegistrarDevolucionPrestamo.php";

$idPrestamo = trim($_POST["idPrestamo"] ?? "");
$fechaEntrada = trim($_POST["fechaDevolucion"] ?? "");
$fechaDevolucion = DateTime::createFromFormat("Y-m-d\\TH:i", $fechaEntrada);

if ($idPrestamo === "" || $fechaDevolucion === false) {
    header("Location: prestamos.php?error=datos_incorrectos");
    exit;
}

$conectorPDO = new ConectorPDO($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_CLAVE"], $_ENV["DB_NOMBRE"]);
$conexion = $conectorPDO->establecerConexion();
$resultado = (new RegistrarDevolucionPrestamo($conexion))->registrarDevolucion(
    $idPrestamo,
    $fechaDevolucion->format("Y-m-d H:i:s")
);
$conectorPDO->desconectar();

header("Location: prestamos.php?" . ($resultado ? "exito=devolucion" : "error=error_devolucion"));
exit;
