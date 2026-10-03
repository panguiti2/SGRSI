<?php

/** Unifica el acceso a solicitudes e incidencias almacenadas como tickets. */
class TicketDAO
{
    private PDO $conexion;

    /** Recibe la conexión PDO utilizada por todas las consultas del ticket. */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    /** Lista todos los tickets o los creados por una cédula. */
    public function listarTickets(?string $cedula = null, ?string $tipo = null): array
    {
        $sql = "SELECT t.id, t.cedulaSolicitante, t.cedulaTecnico,
                t.idLaboratorio, t.numeroDispositivo, t.fechaApertura,
                t.fechaCierre, t.fechaGestion, t.grupo, t.nombreDocente,
                t.descripcion, t.turno, t.estado, t.asignatura,
                CASE WHEN i.id IS NOT NULL THEN 'INCIDENCIA' ELSE 'SOLICITUD' END AS tipo,
                i.reportoAlumno, i.nombreAlumno, i.diagnostico, i.solucion,
                s.tipoServicio, s.fechaEsperada
            FROM TICKET AS t
            LEFT JOIN INCIDENCIA AS i ON i.id = t.id
            LEFT JOIN SERVICIO AS s ON s.idServicio = t.id";

        if ($cedula !== null) {
            $sql .= " WHERE t.cedulaSolicitante = :cedula";
        }
        if ($tipo !== null) {
            $sql .= $cedula !== null ? " AND " : " WHERE ";
            $sql .= $tipo === "INCIDENCIA" ? "i.id IS NOT NULL" : "s.idServicio IS NOT NULL";
        }
        $sql .= " ORDER BY t.fechaApertura DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute($cedula === null ? [] : ["cedula" => $cedula]);
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Comprueba que el ticket exista y corresponda a la sección indicada. */
    public function listarTicket(string $id, string $tipo): ?array
    {
        $tabla = $tipo === "INCIDENCIA" ? "INCIDENCIA" : "SERVICIO";
        $clave = $tipo === "INCIDENCIA" ? "id" : "idServicio";
        $consulta = $this->conexion->prepare(
            "SELECT t.id, t.estado FROM TICKET AS t
            INNER JOIN " . $tabla . " AS detalle ON detalle." . $clave . " = t.id
            WHERE t.id = :id"
        );
        $consulta->execute(["id" => $id]);
        $ticket = $consulta->fetch(PDO::FETCH_ASSOC);
        return $ticket === false ? null : $ticket;
    }

    /** Comprueba catálogos y dispositivo usados por un ticket. */
    public function existenDatosComunes(
        string $turno,
        string $laboratorio,
        string $dispositivo
    ): bool {
        $consulta = $this->conexion->prepare(
            "SELECT 1
            FROM TURNO AS tu
            INNER JOIN DISPOSITIVO AS d
                ON d.idLaboratorio = :laboratorio
                AND d.numeroDispositivo = :dispositivo
            WHERE tu.codigoTurno = :turno"
        );
        $consulta->execute([
            "turno" => $turno,
            "laboratorio" => $laboratorio,
            "dispositivo" => $dispositivo
        ]);
        return $consulta->fetchColumn() !== false;
    }

    /** Comprueba un tipo de servicio. */
    public function existeTipoServicio(string $tipoServicio): bool
    {
        $consulta = $this->conexion->prepare(
            "SELECT 1 FROM TIPO_SERVICIO WHERE codigoTipoServicio = :tipo"
        );
        $consulta->execute(["tipo" => $tipoServicio]);
        return $consulta->fetchColumn() !== false;
    }

    /** Comprueba un estado de ticket. */
    public function existeEstado(string $estado): bool
    {
        $consulta = $this->conexion->prepare(
            "SELECT 1 FROM ESTADO_TICKET WHERE codigoEstado = :estado"
        );
        $consulta->execute(["estado" => $estado]);
        return $consulta->fetchColumn() !== false;
    }

    /** Registra una solicitud en TICKET y SERVICIO. */
    public function registrarSolicitud(array $datos): bool
    {
        try {
            $this->conexion->beginTransaction();
            $this->insertarTicket($datos);
            $consulta = $this->conexion->prepare(
                "INSERT INTO SERVICIO (idServicio, tipoServicio, fechaEsperada)
                VALUES (:id, :tipoServicio, :fechaEsperada)"
            );
            $consulta->execute([
                "id" => $datos["id"],
                "tipoServicio" => $datos["tipoServicio"],
                "fechaEsperada" => $datos["fechaEsperada"]
            ]);
            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            return false;
        }
    }

    /** Registra una incidencia en TICKET e INCIDENCIA. */
    public function registrarIncidencia(array $datos): bool
    {
        try {
            $this->conexion->beginTransaction();
            $this->insertarTicket($datos);
            $consulta = $this->conexion->prepare(
                "INSERT INTO INCIDENCIA (id, reportoAlumno, nombreAlumno)
                VALUES (:id, :reportoAlumno, :nombreAlumno)"
            );
            $consulta->execute([
                "id" => $datos["id"],
                "reportoAlumno" => $datos["reportoAlumno"] ? 1 : 0,
                "nombreAlumno" => $datos["nombreAlumno"]
            ]);
            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            return false;
        }
    }

    /** Inserta los datos compartidos por ambos tipos de ticket. */
    private function insertarTicket(array $datos): void
    {
        $consulta = $this->conexion->prepare(
            "INSERT INTO TICKET (
                id, cedulaSolicitante, idLaboratorio, numeroDispositivo,
                fechaApertura, grupo, nombreDocente, descripcion,
                turno, estado, asignatura
            ) VALUES (
                :id, :cedulaSolicitante, :idLaboratorio, :numeroDispositivo,
                :fechaApertura, :grupo, :nombreDocente, :descripcion,
                :turno, 'PENDIENTE', :asignatura
            )"
        );
        $consulta->execute([
            "id" => $datos["id"],
            "cedulaSolicitante" => $datos["cedulaSolicitante"],
            "idLaboratorio" => $datos["idLaboratorio"],
            "numeroDispositivo" => $datos["numeroDispositivo"],
            "fechaApertura" => $datos["fechaApertura"],
            "grupo" => $datos["grupo"],
            "nombreDocente" => $datos["nombreDocente"],
            "descripcion" => $datos["descripcion"],
            "turno" => $datos["turno"],
            "asignatura" => $datos["asignatura"]
        ]);
    }

    /** Actualiza el estado de una solicitud. */
    public function actualizarSolicitud(string $id, string $estado): bool
    {
        try {
            $consulta = $this->conexion->prepare(
                "UPDATE TICKET
                SET estado = :estado,
                    fechaCierre = CASE WHEN :cierre = 'RESUELTO' THEN NOW() ELSE NULL END
                WHERE id = :id"
            );
            $consulta->execute(["id" => $id, "estado" => $estado, "cierre" => $estado]);
            return true;
        } catch (PDOException $error) {
            return false;
        }
    }

    /** Gestiona una incidencia y registra diagnóstico y solución. */
    public function gestionarIncidencia(array $datos): bool
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = $this->conexion->prepare(
                "UPDATE TICKET
                SET estado = :estado, cedulaTecnico = :cedulaTecnico,
                    fechaGestion = NOW(),
                    fechaCierre = CASE WHEN :cierre = 'RESUELTO' THEN NOW() ELSE NULL END
                WHERE id = :id"
            );
            $consulta->execute([
                "id" => $datos["id"],
                "estado" => $datos["estado"],
                "cierre" => $datos["estado"],
                "cedulaTecnico" => $datos["cedulaTecnico"]
            ]);

            $consultaIncidencia = $this->conexion->prepare(
                "UPDATE INCIDENCIA
                SET diagnostico = :diagnostico, solucion = :solucion
                WHERE id = :id"
            );
            $consultaIncidencia->execute([
                "id" => $datos["id"],
                "diagnostico" => $datos["diagnostico"],
                "solucion" => $datos["solucion"]
            ]);

            return $this->conexion->commit();
        } catch (PDOException $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            return false;
        }
    }
}
