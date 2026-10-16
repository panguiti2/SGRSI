<?php

/** Traduce conflictos previstos de integridad sin mostrar consultas SQL. */
class ErrorSql
{
    public static function traducir(PDOException $error, string $duplicado): void
    {
        $codigo = (int) ($error->errorInfo[1] ?? 0);
        if ($codigo === 1062) throw new ErrorHttp($duplicado, 409);
        if ($codigo === 1451) {
            throw new ErrorHttp('No se puede realizar la operación porque existen registros asociados', 409);
        }
        if ($codigo === 1452) throw new ErrorHttp('Los datos relacionados no existen', 422);
        if (in_array($codigo, [1205, 1213], true)) {
            throw new ErrorHttp('La operación está en curso. Intente nuevamente', 409);
        }
        throw $error;
    }
}
