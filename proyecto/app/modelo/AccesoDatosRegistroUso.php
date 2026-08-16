<?php
/**
 * Consulta los registros de uso almacenados en la base de datos.
 */
class AccesoDatosRegistroUso
{
    private PDO $conexion;
    /**
     * Inicializa el acceso a datos con una conexión activa.
     * @param PDO $conexion Conexión activa con la base de datos.
     */
    public function __construct(PDO $conexion) { $this->conexion = $conexion; }

    /**
     * Obtiene los registros de uso, opcionalmente filtrados por solicitante.
     * @param string|null $cedulaSolicitante Cédula por la cual filtrar o null para listar todos.
     * @return array Registros de uso encontrados.
     */
    public function listarRegistros(?string $cedulaSolicitante = null): array
    {
        $sql = "SELECT idRegistro, cedulaSolicitante, laboratorio, turno, fechaHora, docente, grupo, asignatura, usoMaquinas, huboIncidencias FROM REGISTRO_USO";
        if ($cedulaSolicitante !== null) $sql .= " WHERE cedulaSolicitante = :cedulaSolicitante";
        $sql .= " ORDER BY fechaHora DESC";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($cedulaSolicitante === null ? [] : ["cedulaSolicitante" => $cedulaSolicitante]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
