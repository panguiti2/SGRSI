<?php
$conexion = new PDO('mysql:host=127.0.0.1;port=3317;dbname=sgrsi_api_prueba;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
if ((int) $conexion->query('SELECT @@port')->fetchColumn() !== 3317) throw new RuntimeException('Instancia incorrecta');
foreach (['INCQA001','SOLQA001'] as $id) {
    $consulta=$conexion->prepare("INSERT INTO TICKET (id,cedulaSolicitante,idLaboratorio,numeroDispositivo,fechaApertura,grupo,nombreDocente,descripcion,turno,asignatura) VALUES (?, '33333333', 'LAB01', 'PC01', NOW(), '3MB', 'Docente de prueba', 'Prueba visual de formulario', 'MATUTINO', 'Programación')");
    $consulta->execute([$id]);
}
$conexion->exec("INSERT INTO INCIDENCIA (id,reportoAlumno) VALUES ('INCQA001',FALSE)");
$conexion->exec("INSERT INTO SERVICIO (idServicio,tipoServicio,fechaEsperada) VALUES ('SOLQA001','INSTALACION','2026-10-09 12:00:00')");
echo "Formularios de gestión preparados exclusivamente en la base ficticia\n";
