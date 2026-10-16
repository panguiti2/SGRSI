<?php
// Exclusivamente para una instancia aislada de MariaDB en 127.0.0.1:3317.
$conexion = new PDO('mysql:host=127.0.0.1;port=3317;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
if ((int) $conexion->query('SELECT @@port')->fetchColumn() !== 3317) throw new RuntimeException('Instancia de prueba incorrecta');
if (($argv[1] ?? '') === '--reiniciar') $conexion->exec('DROP DATABASE IF EXISTS sgrsi_api_prueba');
$conexion->exec('CREATE DATABASE sgrsi_api_prueba CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$conexion->exec('USE sgrsi_api_prueba');
foreach (['creacionUsuarios', 'creacionCatalogos', 'creacionDispositivo', 'creacionTicket', 'creacionPrestamo', 'creacionRegistroUso'] as $archivo) {
    $conexion->exec(file_get_contents(__DIR__ . '/../baseDeDatos/DDL/' . $archivo . '.sql'));
}
$conexion->exec(file_get_contents(__DIR__ . '/../baseDeDatos/DML/inserccionCatalogos.sql'));
foreach (['administrador' => '11111111', 'tecnico' => '22222222', 'solicitante' => '33333333'] as $rol => $cedula) {
    $consulta = $conexion->prepare('INSERT INTO USUARIO VALUES (?, ?, ?, ?, TRUE)');
    $consulta->execute([$cedula, 'Prueba', 'Aislada', password_hash('PruebaAislada123!', PASSWORD_DEFAULT)]);
    $conexion->exec('INSERT INTO ' . strtoupper($rol) . " VALUES ('$cedula')");
}
$conexion->exec("INSERT INTO LABORATORIO VALUES ('LAB01','Laboratorio 1'), ('LAB02','Laboratorio 2')");
$conexion->exec("INSERT INTO DISPOSITIVO (idLaboratorio,numeroDispositivo) VALUES ('LAB01','PC01')");
echo "DATOS_AISLADOS_OK\n";
