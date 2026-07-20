<?php

session_start();

if (!isset($_SESSION["cedula"])) {
    header("Location: login.php?error=sin_sesion");
    exit;
}

if (empty($_SESSION["administrador"]) || empty($_SESSION["tecnico"])) {
    header("Location: login.php?error=no_autorizado");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selección de panel | SGRSI</title>
</head>
<body>
    <main>
        <h1>Seleccioná el panel al que querés ingresar</h1>
        <p><a href="administrador.php">Panel de administración</a></p>
        <p><a href="tecnico.php">Panel técnico</a></p>
        <p><a href="cerrarSesion.php">Cerrar sesión</a></p>
    </main>
</body>
</html>
