<?php
require_once __DIR__ . '/../migracion_api/config/config.php';
$total = 0;
function comprobar(bool $condicion): void {
    global $total;
    if (!$condicion) throw new RuntimeException('Prueba fallida');
    $total++;
}
foreach (['', '  ', null] as $vacio) {
    try { Peticion::validarCamposObligatorios([$vacio]); comprobar(false); }
    catch (ErrorHttp $error) { comprobar($error->getMessage() === 'Existen campos vacios'); }
}
Peticion::validarCamposObligatorios(['0', 'NO', 'valor']); comprobar(true);
comprobar(Peticion::fecha('2026-10-08T13:00')->format('s') === '00');
foreach (['2026-02-30T13:00', '2026-10-08', '2026-10-08T25:00'] as $invalida) {
    try { Peticion::fecha($invalida); comprobar(false); }
    catch (ErrorHttp $error) { comprobar($error->getCode() === 422); }
}
try { Peticion::validarLongitudes(['nombre'=>str_repeat('á',51)], ['nombre'=>50]); comprobar(false); }
catch (ErrorHttp $error) { comprobar($error->getCode() === 422); }
comprobar(ConectorPDO::obtenerInstancia() === ConectorPDO::obtenerInstancia());
try { serialize(ConectorPDO::obtenerInstancia()); comprobar(false); }
catch (LogicException $error) { comprobar(true); }
$_SESSION = ['cedula'=>'33333333','solicitante'=>true];
$token = Token::generarTokenCSRF();
comprobar(strlen($token) === 64 && $token === Token::generarTokenCSRF());
$_SERVER['HTTP_X_CSRF_TOKEN'] = $token;
Token::verificarCsrf(); comprobar(true);
$_SERVER['HTTP_X_CSRF_TOKEN'] = 'incorrecto';
try { Token::verificarCsrf(); comprobar(false); }
catch (ErrorHttp $error) { comprobar($error->getCode() === 403); }
Sesion::verificarRoles(['solicitante']); comprobar(true);
try { Sesion::verificarRoles(['administrador']); comprobar(false); }
catch (ErrorHttp $error) { comprobar($error->getCode() === 403); }
foreach ([[1062,409],[1451,409],[1452,422],[1205,409],[1213,409]] as [$codigo,$estado]) {
    $errorSql = new PDOException('detalle interno');
    $errorSql->errorInfo = ['23000', $codigo, 'detalle interno'];
    try { ErrorSql::traducir($errorSql,'Duplicado controlado'); comprobar(false); }
    catch (ErrorHttp $error) { comprobar($error->getCode() === $estado && !str_contains($error->getMessage(),'detalle interno')); }
}
$inesperado = new PDOException('inesperado');
try { ErrorSql::traducir($inesperado,'Duplicado'); comprobar(false); }
catch (PDOException $error) { comprobar($error === $inesperado); }
foreach (['corta',str_repeat('x',73),str_repeat('á',6)] as $clave) {
    try { Peticion::validarClave($clave,$clave); comprobar(false); }
    catch (ErrorHttp $error) { comprobar($error->getCode() === 422); }
}
Peticion::validarClave('PruebaValida123!','PruebaValida123!'); comprobar(true);
try { Peticion::validarClave('PruebaValida123!','Diferente123456'); comprobar(false); }
catch (ErrorHttp $error) { comprobar($error->getCode() === 422); }
$_GET = ['cedula'=>' 33333333 '];
comprobar(Peticion::recibirConsulta(['cedula'])['cedula'] === '33333333');
$_GET = ['cedula'=>' '];
try { Peticion::recibirConsulta(['cedula']); comprobar(false); }
catch (ErrorHttp $error) { comprobar($error->getMessage() === 'Existen campos vacios'); }
$_GET = ['cedula'=>['33333333']];
try { Peticion::recibirConsulta(['cedula']); comprobar(false); }
catch (ErrorHttp $error) { comprobar($error->getCode() === 422); }
echo "$total comprobaciones de núcleo correctas\n";
