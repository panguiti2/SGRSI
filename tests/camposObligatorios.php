<?php

require_once __DIR__ . '/../proyecto/app/nucleo/ErrorHttp.php';
require_once __DIR__ . '/../proyecto/app/nucleo/Peticion.php';

// Ejecutar por separado: las respuestas de error finalizan la petición.
$casos = [
    'vacio' => [''],
    'espacios' => ['   '],
    'mixto' => ['dato', ''],
    'cero' => ['0'],
    'no' => ['NO'],
    'valido' => ['dato', 'otro']
];
$caso = $argv[1] ?? 'valido';
if (!isset($casos[$caso])) {
    exit(1);
}
try {
    Peticion::validarCamposObligatorios($casos[$caso]);
    echo 'VALIDO';
} catch (ErrorHttp $error) {
    echo json_encode(['mensaje' => $error->getMessage()], JSON_UNESCAPED_UNICODE);
}
