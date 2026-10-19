<?php

require_once __DIR__ . '/../proyecto/config/config.php';

$raiz = realpath(__DIR__ . '/../proyecto');
if (realpath(RUTA_PROYECTO) !== $raiz) {
    throw new RuntimeException('La configuración debe pertenecer al proyecto actual');
}
foreach (['.env', 'composer.json', 'composer.lock', 'vendor/autoload.php'] as $archivo) {
    if (!is_file($raiz . '/' . $archivo)) {
        throw new RuntimeException('Falta una dependencia local: ' . $archivo);
    }
}
foreach (get_included_files() as $archivo) {
    $ruta = str_replace('\\', '/', realpath($archivo));
    $permitido = str_replace('\\', '/', $raiz) . '/';
    if ($archivo !== __FILE__ && !str_starts_with($ruta, $permitido)) {
        throw new RuntimeException('La configuración carga archivos externos al proyecto');
    }
}
foreach (['DB_HOST', 'DB_USUARIO', 'DB_CLAVE', 'DB_NOMBRE'] as $variable) {
    if (!array_key_exists($variable, $_ENV)) {
        throw new RuntimeException('Falta una variable de configuración: ' . $variable);
    }
}
echo "Configuración y dependencias independientes correctas, sin conectar a la base.\n";
