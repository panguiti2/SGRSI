<?php

/**
 * Recupera y valida los datos que se usan como opciones de los formularios.
 */
class AccesoDatosCatalogo
{
    private PDO $conexion;

    /** @param PDO $conexion Conexión PDO activa. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /** @return array Turnos disponibles. */
    public function listarTurnos(): array
    {
        return $this->listar("TURNO", "codigoTurno");
    }

    /** @return array Tipos de servicio disponibles. */
    public function listarTiposServicio(): array
    {
        return $this->listar("TIPO_SERVICIO", "codigoTipoServicio");
    }

    /** @return array Modificaciones disponibles para un dispositivo. */
    public function listarModificacionesDispositivo(): array
    {
        return $this->listar("MODIFICACION_DISPOSITIVO", "codigoModificacion");
    }

    /** @return array Estados disponibles para los tickets. */
    public function listarEstadosTicket(): array
    {
        return $this->listar("ESTADO_TICKET", "codigoEstado");
    }

    /** @param string $codigoTurno Código a comprobar. @return bool True si existe. */
    public function existeTurno(string $codigoTurno): bool
    {
        return $this->existe("TURNO", "codigoTurno", $codigoTurno);
    }

    /** @param string $codigoTipoServicio Código a comprobar. @return bool True si existe. */
    public function existeTipoServicio(string $codigoTipoServicio): bool
    {
        return $this->existe("TIPO_SERVICIO", "codigoTipoServicio", $codigoTipoServicio);
    }

    /** @param string $codigoModificacion Código a comprobar. @return bool True si existe. */
    public function existeModificacionDispositivo(string $codigoModificacion): bool
    {
        return $this->existe("MODIFICACION_DISPOSITIVO", "codigoModificacion", $codigoModificacion);
    }

    /** @param string $codigoEstado Estado a comprobar. @return bool True si existe. */
    public function existeEstadoTicket(string $codigoEstado): bool
    {
        return $this->existe("ESTADO_TICKET", "codigoEstado", $codigoEstado);
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
