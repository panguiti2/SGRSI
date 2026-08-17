<?php

/**
 * Recupera y valida los datos que se usan como opciones de los formularios.
 */
class AccesoDatosCatalogo
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function listarTurnos(): array
    {
        return $this->listar("TURNO", "codigoTurno");
    }

    public function listarTiposServicio(): array
    {
        return $this->listar("TIPO_SERVICIO", "codigoTipoServicio");
    }

    public function listarModificacionesDispositivo(): array
    {
        return $this->listar("MODIFICACION_DISPOSITIVO", "codigoModificacion");
    }

    public function existeTurno(string $codigoTurno): bool
    {
        return $this->existe("TURNO", "codigoTurno", $codigoTurno);
    }

    public function existeTipoServicio(string $codigoTipoServicio): bool
    {
        return $this->existe("TIPO_SERVICIO", "codigoTipoServicio", $codigoTipoServicio);
    }

    public function existeModificacionDispositivo(string $codigoModificacion): bool
    {
        return $this->existe("MODIFICACION_DISPOSITIVO", "codigoModificacion", $codigoModificacion);
    }

    private function listar(string $tabla, string $columna): array
    {
        $consulta = $this->conexion->query(
            "SELECT $columna AS codigo, nombre FROM $tabla ORDER BY nombre"
        );

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    private function existe(string $tabla, string $columna, string $codigo): bool
    {
        $consulta = $this->conexion->prepare(
            "SELECT 1 FROM $tabla WHERE $columna = :codigo"
        );
        $consulta->execute(["codigo" => $codigo]);

        return $consulta->fetchColumn() !== false;
    }
}
