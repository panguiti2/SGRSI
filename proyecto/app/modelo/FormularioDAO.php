<?php

/** Recupera las opciones de los formularios desde la base de datos. */
class FormularioDAO
{
    private PDO $conexion;

    /** Recibe la conexión PDO. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /** Recupera los laboratorios. */
    public function listarLaboratorios(): array
    {
        $consulta = $this->conexion->query("SELECT idLaboratorio, nombre FROM LABORATORIO ORDER BY idLaboratorio");
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Recupera los dispositivos para seleccionar por laboratorio. */
    public function listarDispositivos(): array
    {
        $consulta = $this->conexion->query("SELECT idLaboratorio, numeroDispositivo FROM DISPOSITIVO ORDER BY idLaboratorio, numeroDispositivo");
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Recupera los turnos. */
    public function listarTurnos(): array
    {
        $consulta = $this->conexion->query("SELECT codigoTurno AS codigo, nombre FROM TURNO ORDER BY codigoTurno");
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Recupera los tipos de servicio. */
    public function listarTiposServicio(): array
    {
        $consulta = $this->conexion->query("SELECT codigoTipoServicio AS codigo, nombre FROM TIPO_SERVICIO ORDER BY codigoTipoServicio");
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Recupera las modificaciones de dispositivos. */
    public function listarModificaciones(): array
    {
        $consulta = $this->conexion->query("SELECT codigoModificacion AS codigo, nombre FROM MODIFICACION_DISPOSITIVO ORDER BY codigoModificacion");
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Recupera los estados de tickets. */
    public function listarEstados(): array
    {
        $consulta = $this->conexion->query("SELECT codigoEstado AS codigo, nombre FROM ESTADO_TICKET ORDER BY codigoEstado");
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
