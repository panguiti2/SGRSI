<?php
require_once __DIR__ . '/../migracion_api/config/config.php';
require_once RUTA_MODELO . '/PrestamoDAO.php';
$opciones = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false];
$dsn = 'mysql:host=127.0.0.1;port=3317;dbname=sgrsi_api_prueba;charset=utf8mb4';
$control = new PDO($dsn, 'root', '', $opciones);
$otra = new PDO($dsn, 'root', '', $opciones);
if ((int) $control->query('SELECT @@port')->fetchColumn() !== 3317) throw new RuntimeException('Instancia incorrecta');
$dao = new PrestamoDAO($otra);
$total = 0;
function exigir(bool $correcto): void {
    global $total;
    if (!$correcto) throw new RuntimeException('Falló el control de concurrencia');
    $total++;
}
exigir((int) $control->query("SELECT GET_LOCK('SGRSI_PRESTAMO_90000001', 0)")->fetchColumn() === 1);
try {
    $dao->registrarPrestamo('PRELOCK1','90000001','MATUTINO','Prueba bloqueo','LT-LOCK','2026-10-08 10:00:00','2026-10-09 10:00:00');
    exigir(false);
} catch (ErrorHttp $error) { exigir($error->getCode() === 409); }
finally { $control->query("SELECT RELEASE_LOCK('SGRSI_PRESTAMO_90000001')"); }
exigir(!$dao->tienePrestamoActivo('90000001'));
exigir($dao->registrarPrestamo('PRELOCK1','90000001','MATUTINO','Prueba bloqueo','LT-LOCK','2026-10-08 10:00:00','2026-10-09 10:00:00'));
try {
    $dao->registrarPrestamo('PRELOCK2','90000001','MATUTINO','Prueba duplicada','LT-LOCK','2026-10-08 10:00:00','2026-10-09 10:00:00');
    exigir(false);
} catch (ErrorHttp $error) { exigir($error->getMessage() === 'La cédula ya posee un préstamo activo'); }
exigir($control->query("SELECT IS_USED_LOCK('SGRSI_PRESTAMO_90000001')")->fetchColumn() === null);
exigir((int) $control->query("SELECT COUNT(*) FROM PRESTAMO WHERE cedulaSolicitante='90000001' AND estado='ACTIVO'")->fetchColumn() === 1);
echo "$total comprobaciones de bloqueo entre conexiones correctas\n";
