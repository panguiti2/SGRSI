<?php

/** Unifica las operaciones de acceso a datos de registros de uso. */
class RegistroUsoDAO
{
    private PDO $conexion;

    /** Recibe la conexión PDO utilizada para registrar y consultar usos. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /** Lista todos los registros o los de una cédula. */
    public function listarRegistros(?string $cedula = null): array
    {
        $sql = "SELECT r.idRegistro, r.cedulaSolicitante,
                CONCAT(u.nombre, ' ', u.apellido) AS solicitante,
                r.idLaboratorio, l.nombre AS laboratorio, r.turno,
                r.fechaHora, r.nombreDocente, r.grupo, r.asignatura,
                r.usoMaquinas, r.huboIncidencias
            FROM REGISTRO_USO AS r
            INNER JOIN USUARIO AS u ON u.cedula = r.cedulaSolicitante
            INNER JOIN LABORATORIO AS l ON l.idLaboratorio = r.idLaboratorio";

        if ($cedula !== null) {
            $sql .= " WHERE r.cedulaSolicitante = :cedula";
        }
        $sql .= " ORDER BY r.fechaHora DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($cedula === null ? [] : ["cedula" => $cedula]);

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Comprueba laboratorio y turno. */
    public function existenOpciones(string $laboratorio, string $turno): bool
    {
        $consulta = $this->conexion->prepare(
            "SELECT 1
            FROM LABORATORIO AS l, TURNO AS t
            WHERE l.idLaboratorio = :laboratorio
                AND t.codigoTurno = :turno"
        );
        $consulta->execute(["laboratorio" => $laboratorio, "turno" => $turno]);
        return $consulta->fetchColumn() !== false;
    }

    /** Registra un uso de laboratorio. */
    public function registrar(array $registro): bool
    {
        $registro["usoMaquinas"] = $registro["usoMaquinas"] ? 1 : 0;
        $registro["huboIncidencias"] = $registro["huboIncidencias"] ? 1 : 0;
        try {
            $consulta = $this->conexion->prepare(
                "INSERT INTO REGISTRO_USO (
                    idRegistro, cedulaSolicitante, idLaboratorio, turno,
                    fechaHora, nombreDocente, grupo, asignatura,
                    usoMaquinas, huboIncidencias
                ) VALUES (
                    :idRegistro, :cedulaSolicitante, :idLaboratorio, :turno,
                    :fechaHora, :nombreDocente, :grupo, :asignatura,
                    :usoMaquinas, :huboIncidencias
                )"
            );
            return $consulta->execute($registro);
        } catch (PDOException $error) {
            return false;
        }
    }
}
