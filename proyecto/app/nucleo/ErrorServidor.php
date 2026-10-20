<?php

/** Explica fallos conocidos sin publicar consultas, rutas ni credenciales. */
class ErrorServidor
{
    public static function respuesta(Throwable $error): array
    {
        if ($error instanceof ErrorHttp) {
            return ['estado' => $error->getCode(), 'mensaje' => $error->getMessage()];
        }
        if ($error instanceof PDOException) {
            $codigo = (int) ($error->errorInfo[1] ?? 0);
            return match ($codigo) {
                1044, 1045 => self::fallo('La aplicación no pudo acceder a la base de datos. El administrador debe revisar las credenciales y los permisos'),
                1142, 1143, 1227 => self::fallo('La cuenta de la base de datos no tiene permisos para realizar esta operación. Contacte al administrador'),
                1049, 1054, 1146 => self::fallo('La estructura de la base de datos no coincide con la aplicación. El administrador debe revisar la importación'),
                1267, 1271 => self::fallo('La base de datos contiene codificaciones incompatibles. El administrador debe revisar la importación'),
                2002, 2003, 2006, 2013 => self::fallo('No se pudo mantener la conexión con la base de datos. Intente nuevamente; si persiste, contacte al administrador', 503),
                default => self::fallo('No se pudo completar la operación en la base de datos. Contacte al administrador para revisar el registro del servidor'),
            };
        }
        if ($error instanceof Error && str_contains($error->getMessage(), 'undefined function mb_')) {
            return self::fallo('Falta habilitar la extensión mbstring de PHP en el servidor. Contacte al administrador');
        }
        return self::fallo('Ocurrió un fallo inesperado al procesar la operación. Contacte al administrador para revisar el registro del servidor');
    }

    private static function fallo(string $mensaje, int $estado = 500): array
    {
        return ['estado' => $estado, 'mensaje' => $mensaje];
    }
}
